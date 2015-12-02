<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class AppointmentRequest extends Model
{
    protected $table = 'appointment_requests';
    protected $fillable = ['from_user_id', 'to_user_id', 'start_date', 'end_date', 'is_confirmed', 'is_cancelled'];

    public function fromUser()
    {
        return $this->belongsTo('App\User', 'from_user_id');
    }

    public function toUser()
    {
        return $this->belongsTo('App\User', 'to_user_id');
    }

    public function appointmentRequestsFrom($user_id)
    {
        $sql = $this->appointmentRequests();
        $sql .= 'where ar.from_user_id = ?';
        $result = DB::select($sql, [$user_id]);
        return $result;
    }

    public function appointmentRequestsTo($user_id)
    {
        $sql = $this->appointmentRequests();
        $sql .= 'where ar.to_user_id = ?';
        return DB::select($sql, [$user_id]);
    }

    private function appointmentRequests()
    {
        return <<<EOT
select ar.id, ar.from_user_id, ar.to_user_id, ar.start_date, ar.end_date, ar.is_confirmed, ar.is_cancelled,
  u_from.first_name || ' ' || u_from.last_name from_user,
  u_to.first_name || ' ' || u_to.last_name to_user
from appointment_requests ar
inner join users u_from on (ar.from_user_id = u_from.id)
inner join users u_to on (ar.to_user_id = u_to.id)
EOT;
    }
}
