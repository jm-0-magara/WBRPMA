<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Houses;
use App\Models\Reservations;
use Illuminate\Support\Facades\Storage;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use DB;

class ReservationController extends Controller
{
    public function reservationView()
    {
        $rentalNo = Session::get('rentalNo');

        // Fetch all reservations for the current rental property
        $reservations = Reservations::where('rentalNo', $rentalNo)
            ->with('house') // Assuming a relationship with the 'Houses' model
            ->orderBy('reservationDate', 'desc')
            ->get();

        // Calculate statistics for the dashboard cards
        $totalReservations = $reservations->count();
        $confirmedReservations = $reservations->where('status', 'confirmed')->count();
        $pendingReservations = $reservations->where('status', 'pending')->count();
        $expiredReservations = $reservations->where('reservationDate', '<', Carbon::now()->toDateString())
            ->where('status', 'pending')
            ->count();

        // Fetch all available houses (where status is Vacant) to populate the form dropdown
        $availableHouses = Houses::where('rentalNo', $rentalNo)
            ->where('status', 'Vacant')
            ->orderBy('houseNo', 'asc')
            ->get();
        
        // Pass the data to the reservations view
        return view('pages.reservations', compact(
            'reservations',
            'totalReservations',
            'confirmedReservations',
            'pendingReservations',
            'expiredReservations',
            'availableHouses'
        ));
    }
    public function addReservation(Request $request)
    {
         $request->validate([
            'client_name' => 'required|string|max:255',
            'client_phone' => 'required|string|max:20',
            'client_email' => 'nullable|email|max:255',
            'client_id_no' => 'nullable|string|max:20',
            'house_number' => 'required|exists:houses,houseNo', // Ensure the house exists
            'entry_date' => 'required|date|after_or_equal:today', // Reservation date cannot be in the past
            'status' => 'required|string|in:pending,confirmed,cancelled',
            'notes' => 'nullable|string',
        ]);

        // Create a new reservation record
        try {
            $reservation = new Reservations();
            $reservation->rentalNo = Session::get('rentalNo');
            $reservation->houseNo = $request->house_number;
            $reservation->clientName = $request->client_name;
            $reservation->clientPhoneNo = $request->client_phone;
            $reservation->clientEmail = $request->client_email;
            $reservation->clientIDNo = $request->client_id_no;
            $reservation->reservationDate = $request->entry_date;
            $reservation->status = $request->status;
            $reservation->notes = $request->notes;
            $reservation->timeRecorded = Carbon::now();
            $reservation->save();

            $house = Houses::where('houseNo', $request->house_number)->first();
            if ($house) {
                $house->status = 'Reserved';
                $house->save();
            }

            Toastr::success('Reservation added successfully :)', 'Success');
        } catch (\Exception $e) {
            Toastr::error('An error occurred: ' . $e->getMessage(), 'Error');
        }

        return redirect()->back();
    }

    public function show($id)
    {
        $reservation = Reservations::findOrFail($id);
        return response()->json($reservation);
    }

    public function updateReservation(Request $request, $id)
    {
        $request->validate([
            'client_name' => 'required|string|max:255',
            'client_phone' => 'required|string|max:20',
            'client_email' => 'nullable|email|max:255',
            'client_id_no' => 'nullable|string|max:20',
            'house_number' => 'required|exists:houses,houseNo',
            'entry_date' => 'required|date|after_or_equal:today',
            'status' => 'required|string|in:pending,confirmed,cancelled',
            'notes' => 'nullable|string',
        ]);

        try {
            $reservation = Reservations::findOrFail($id);

            DB::transaction(function () use ($request, $reservation) {
                // If the house number is changing, update the status of the old house
                if ($reservation->houseNo !== $request->house_number) {
                    $oldHouse = Houses::where('houseNo', $reservation->houseNo)->first();
                    if ($oldHouse) {
                        $oldHouse->status = 'Vacant';
                        $oldHouse->save();
                    }

                    // Update the status of the new house
                    $newHouse = Houses::where('houseNo', $request->house_number)->first();
                    if ($newHouse) {
                        $newHouse->status = 'Reserved';
                        $newHouse->save();
                    }
                }
                
                // If the reservation status changes to 'cancelled', make the house vacant
                if ($request->status === 'cancelled' && $reservation->status !== 'cancelled') {
                    $house = Houses::where('houseNo', $reservation->houseNo)->first();
                    if ($house) {
                        $house->status = 'Vacant';
                        $house->save();
                    }
                } elseif ($request->status !== 'cancelled' && $reservation->status === 'cancelled') {
                    // If the reservation status changes from 'cancelled' to something else, make the house reserved again
                    $house = Houses::where('houseNo', $reservation->houseNo)->first();
                    if ($house) {
                        $house->status = 'Reserved';
                        $house->save();
                    }
                }

                // Update the reservation record
                $reservation->houseNo = $request->house_number;
                $reservation->clientName = $request->client_name;
                $reservation->clientPhoneNo = $request->client_phone;
                $reservation->clientEmail = $request->client_email;
                $reservation->clientIDNo = $request->client_id_no;
                $reservation->reservationDate = $request->entry_date;
                $reservation->status = $request->status;
                $reservation->notes = $request->notes;
                $reservation->save();
            });

            Toastr::success('Reservation updated successfully :)', 'Success');
        } catch (\Exception $e) {
            Toastr::error('An error occurred: ' . $e->getMessage(), 'Error');
        }

        return redirect()->back();
    }

    public function deleteReservation($id)
    {
        try {
            $reservation = Reservations::findOrFail($id);

            $house = Houses::where('houseNo', $reservation->houseNo)->first();
            if ($house) {
                $house->status = 'Vacant';
                $house->save();
            }

            $reservation->delete();
            Toastr::success('Reservation deleted successfully :)', 'Success');
        } catch (\Exception $e) {
            Toastr::error('An error occurred while deleting the reservation.', 'Error');
        }
        
        return redirect()->back();
    }
}
