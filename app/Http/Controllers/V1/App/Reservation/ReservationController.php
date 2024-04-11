<?php

namespace App\Http\Controllers\V1\App\Reservation;

use App\Events\PublicPlaceReservation;
use App\Http\Requests\V1\App\Reservation\ReservationRequest;
use App\Models\Restaurant;
use App\Events\UpdateUserHall;
use App\Http\Controllers\Controller;
use App\Service\Reservation\ReservationPublicPlaceService;
use App\Service\Reservation\ReservationRestaurantService;
use Illuminate\Http\JsonResponse;


class ReservationController extends Controller
{


    /**
     * @var ReservationRestaurantService
     */
    protected ReservationRestaurantService $reservationService;


    /**
     * @var ReservationPublicPlaceService
     */
    protected ReservationPublicPlaceService $reservationPublicPlaces;

    /**
     * ReservationController constructor.
     * @param ReservationRestaurantService $reservationService
     * @param ReservationPublicPlaceService $reservationPublicPlaces
     */
    public function __construct(ReservationRestaurantService $reservationService, ReservationPublicPlaceService $reservationPublicPlaces)
    {
        $this->reservationService = $reservationService;
        $this->reservationPublicPlaces = $reservationPublicPlaces;
    }


    /**
     * make reservation for restaurant or public place
     * @param ReservationRequest $request
     * @return JsonResponse
     */
    public function reservationFactory(ReservationRequest $request): JsonResponse
    {
        return $request->type == 'restaurant' ? $this->validateParameterOfRestaurant($request) :
            $this->validateParameterOfPublicPlace($request);
    }


    /**
     * reservation for restaurant
     * @param ReservationRequest $request
     * @return JsonResponse
     */
    public function validateParameterOfRestaurant(ReservationRequest $request): JsonResponse
    {
        $data = $request->validated();
        try {
            $restaurant = Restaurant::find($data['restaurant_id']);

            // card check parameters it is implemented in ReservationRestaurantService
            $chair = $this->reservationService->checkCard($restaurant, $data);

            // check if the user is in the place
            // $this->reservationService->checkInPlace($request->myLongitude,$request->myLatitude,$restaurant['my_longitude'],$restaurant['my_latitude']);

            // check if there is a reservation before
            $this->reservationService->handleExistingReservation($request);

            // check if there is a conflicting reservation
            $this->reservationService->handleConflictingReservation($chair, $restaurant, $request);

            // there is no reservation so create a new one
            $reserve = $this->reservationService->createReservation($restaurant, $request, $chair);

            // store history on History Attendance for sending notification to him letter
            $this->reservationService->storeHistory($restaurant, $request);

            UpdateUserHall::dispatch($reserve, $reserve->restaurant_id);
            if (!$reserve) {
                throw new \Exception(__('errors.invalid_parameter'), 405);
            }
            return finalResponse('success', 200, $reserve);
        } catch (\Exception $e) {
            return finalResponse('failed', 400, null, null, $e->getMessage());
        }
    }


    /**
     * reservation for public place
     * @param ReservationRequest $request
     * @return JsonResponse
     */
    public function validateParameterOfPublicPlace(ReservationRequest $request): JsonResponse
    {
        $data = $request->validated();
        $user = $request->user('api');
        try {
            // card check parameters it is implemented in ReservationPublicPlaceService
            $publicPlace = $this->reservationPublicPlaces->checkCard($data);
            // check if the user is in the place

            // check if the user is in the place
            // $this->reservationPublicPlaces->checkInPlace($data['my_latitude'],$data['my_longitude'],$publicPlace['latitude'],$publicPlace['longitude']);

            // check if there is a reservation before
            $this->reservationPublicPlaces->handleExistingReservation($user, $publicPlace);

            // there is no reservation so create a new one
            $reserve = $this->reservationPublicPlaces->createReservation($publicPlace, $user);
            if (!$reserve) {
                throw new \Exception(__('errors.invalid_parameter'), 405);
            }

            // dispatch event to delete the reservation
            PublicPlaceReservation::dispatch($publicPlace->id, $user);

            return finalResponse('success', 200, $reserve);
        } catch (\Exception $e) {
            return finalResponse('failed', 400, null, null, $e->getMessage());
        }
    }


}
