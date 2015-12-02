<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App;
use Auth;
use App\Http\Requests;
use App\Http\Requests\AppointmentRequestRequest;
use App\Http\Controllers\Controller;
use App\Models\AppointmentRequest;
use Swagger\Annotations as SWG;

/**
 * @SWG\Resource(
 *  apiVersion="1.0",
 *  resourcePath="/appointment_requests",
 *  description="AppointmentRequest",
 *  produces="['application/json']"
 * )
 */
class AppointmentRequestController extends Controller
{

    private $appointment_requests;
    private $user;

    public function __construct(AppointmentRequest $appointment_requests)
    {
        $this->appointment_requests = $appointment_requests;
        $this->user = Auth::user();
    }

    /**
     * @SWG\Api(
     *  path="/appointment_requests",
     *      @SWG\Operation(
     *          method="GET",
     *          summary="Returns appointment_requests",
     *          nickname="HTTP GET appointment_requests",
     *          @SWG\Parameter(
     *            name="token",
     *            description="Auth Token",
     *            paramType="query",
     *              required=false,
     *              allowMultiple=false,
     *              type="string",
     *              defaultValue=""
     *          )
     *  )
     * )
     */
    public function index()
    {
        $appointments_from = $this->appointment_requests->appointmentRequestsFrom($this->user->id);
        $appointments_to   = $this->appointment_requests->appointmentRequestsTo($this->user->id);
        return compact('appointments_from', 'appointments_to');
    }

    /**
     * @SWG\Api(
     *  path="/appointment_requests",
     *      @SWG\Operation(
     *          method="POST",
     *          summary="Creates a new appointment_requests",
     *          nickname="HTTP POST appointment_requests",
     *      @SWG\Parameter(
     *          name="token",
     *          description="Auth token",
     *          paramType="query",
     *              required=true,
     *              allowMultiple=false,
     *              type="string"
     *          ),
     *      @SWG\Parameter(
     *          name="from_user_id",
     *          description="From User",
     *          paramType="form",
     *              required=true,
     *              allowMultiple=false,
     *              type="integer"
     *          ),
     *      @SWG\Parameter(
     *          name="to_user_id",
     *          description="To User",
     *          paramType="form",
     *              required=true,
     *              allowMultiple=false,
     *              type="integer"
     *          ),
     *      @SWG\Parameter(
     *          name="date_and_time",
     *          description="Date and Time in format Y-m-d H:i",
     *          paramType="form",
     *              required=true,
     *              allowMultiple=false,
     *              type="string"
     *          ),
     *  )
     * )
     */
    public function store(AppointmentRequestRequest $request)
    {
        return $this->appointment_requests->create($request->all());
    }

    /**
     * @SWG\Api(
     *  path="/appointment_requests/{id}",
     *      @SWG\Operation(
     *          method="GET",
     *          summary="Returns a specific appointment_requests",
     *          nickname="HTTP GET appointment_requests",
     *      @SWG\Parameter(
     *          name="token",
     *          description="Auth token",
     *          paramType="query",
     *              required=true,
     *              allowMultiple=false,
     *              type="string"
     *          ),
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of appointment_requests",
     *          paramType="path",
     *              required=true,
     *              allowMultiple=false,
     *              type="string"
     *          ),
     *  )
     * )
     */
    public function show($id)
    {
        return $this->appointment_requests->find($id);
    }

        /**
     * @SWG\Api(
     *  path="/appointment_requests/{id}",
     *      @SWG\Operation(
     *          method="PATCH",
     *          summary="Updates a specific appointment_requests",
     *          nickname="HTTP PATCH appointment_requests",
     *      @SWG\Parameter(
     *          name="token",
     *          description="Auth token",
     *          paramType="query",
     *              required=true,
     *              allowMultiple=false,
     *              type="string"
     *          ),
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of appointment_requests to update",
     *          paramType="path",
     *              required=true,
     *              allowMultiple=false,
     *              type="string"
     *          ),
     *      @SWG\Parameter(
     *          name="from_user_id",
     *          description="From User",
     *          paramType="form",
     *              required=true,
     *              allowMultiple=false,
     *              type="string"
     *          ),
     *      @SWG\Parameter(
     *          name="to_user_id",
     *          description="To User",
     *          paramType="form",
     *              required=true,
     *              allowMultiple=false,
     *              type="string"
     *          ),
     *      @SWG\Parameter(
     *          name="date_and_time",
     *          description="Date and Time in format Y-m-d H:i",
     *          paramType="form",
     *              required=true,
     *              allowMultiple=false,
     *              type="string"
     *          ),
     *  )
     * )
     */

    /**
     * @SWG\Api(
     *  path="/appointment_requests/{id}",
     *      @SWG\Operation(
     *          method="PUT",
     *          summary="Updates a specific appointment_requests",
     *          nickname="HTTP PUT appointment_requests",
     *      @SWG\Parameter(
     *          name="token",
     *          description="Auth token",
     *          paramType="query",
     *              required=true,
     *              allowMultiple=false,
     *              type="string"
     *          ),
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of appointment_requests to update",
     *          paramType="path",
     *              required=true,
     *              allowMultiple=false,
     *              type="string"
     *          ),
     *      @SWG\Parameter(
     *          name="from_user_id",
     *          description="From user",
     *          paramType="form",
     *              required=true,
     *              allowMultiple=false,
     *              type="string"
     *          ),
     *      @SWG\Parameter(
     *          name="from_user_id",
     *          description="To user",
     *          paramType="form",
     *              required=true,
     *              allowMultiple=false,
     *              type="string"
     *          ),
     *      @SWG\Parameter(
     *          name="date_and_time",
     *          description="Date and Time in format Y-m-d H:i",
     *          paramType="form",
     *              required=true,
     *              allowMultiple=false,
     *              type="string"
     *          ),
     *  )
     * )
     */
    public function update(AppointmentRequestRequest $request, $id)
    {
        return $this->_updateAppointmentRequests($id, $request->all());
    }

    /**
     * @SWG\Api(
     *  path="/appointment_requests/{id}",
     *      @SWG\Operation(
     *          method="DELETE",
     *          summary="Deletes a specific appointment_requests",
     *          nickname="HTTP DELETE appointment_requests",
     *      @SWG\Parameter(
     *          name="token",
     *          description="Auth token",
     *          paramType="query",
     *              required=true,
     *              allowMultiple=false,
     *              type="string"
     *          ),
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of the appointment_requests to remove",
     *          paramType="path",
     *              required=true,
     *              allowMultiple=false,
     *              type="string"
     *          ),
     *  )
     * )
     */
    public function destroy($id)
    {
        $this->appointment_requests->destroy($id);
    }

    /**
     * @SWG\Api(
     *  path="/appointment_requests/{id}/confirm",
     *      @SWG\Operation(
     *          method="PATCH",
     *          summary="Updates a specific appointment_requests",
     *          nickname="HTTP PATCH appointment_requests",
     *      @SWG\Parameter(
     *          name="token",
     *          description="Auth token",
     *          paramType="query",
     *              required=true,
     *              allowMultiple=false,
     *              type="string"
     *          ),
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of appointment_requests to confirm",
     *          paramType="path",
     *              required=true,
     *              allowMultiple=false,
     *              type="string"
     *          )
     *  )
     * )
     */
    public function confirm($id)
    {
        $gc = new App\Libs\GoogleCalendarWrapper($this->user);
        $appointmentRequest = $this->_updateAppointmentRequests($id, ['is_confirmed' => true]);

        $from_user = $appointmentRequest->fromUser;
        $to_user = $appointmentRequest->toUser;


        $start_date = new \DateTime($appointmentRequest->start_date);
        $end_date = new \DateTime($appointmentRequest->end_date);


        $gc->createEvent(
            "Cita: $from_user->first_name - $to_user->first_name",
            'Appointment',
            'Cita creada automaticamente por Appointment',
            $start_date->format(\DateTime::ISO8601),
            $end_date->format(\DateTime::ISO8601),
            [
                'email' => $from_user->email,
                'email' => $to_user->email
            ]
        );
        return $appointmentRequest;
    }

        /**
     * @SWG\Api(
     *  path="/appointment_requests/{id}/cancel",
     *      @SWG\Operation(
     *          method="PATCH",
     *          summary="Updates a specific appointment_requests",
     *          nickname="HTTP PATCH appointment_requests",
     *      @SWG\Parameter(
     *          name="token",
     *          description="Auth token",
     *          paramType="query",
     *              required=true,
     *              allowMultiple=false,
     *              type="string"
     *          ),
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of appointment_requests to confirm",
     *          paramType="path",
     *              required=true,
     *              allowMultiple=false,
     *              type="string"
     *          )
     *  )
     * )
     */
    public function cancel($id)
    {
        return $this->_updateAppointmentRequests($id, ['is_cancelled' => true]);
    }

    private function _updateAppointmentRequests($id, $new_values)
    {
        $appointment_requests = $this->appointment_requests->find($id);
        if (!$appointment_requests) {
            App::abort(404);
        }
        $appointment_requests->fill($new_values);
        $appointment_requests->save();
        return $appointment_requests;
    }
}
