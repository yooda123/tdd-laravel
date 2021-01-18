<?php

namespace App\Models;

use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // 主 -> 従
    public function reservations(): HasMany {
        return $this->hasMany('App\Models\Reservation');
    }

    public function reservationCountThisMonth(): int
    {
        $today = Carbon::today();
        return $this->reservations()
            ->whereYear('created_at', $today->year())
            ->whereMonth('create_at', $today->month())
            ->count();
    }

    public function canReserve(Lesson $lesson): void
    {

        if ($lesson->getVacantCount() === 0) {
            // return false;
            throw new Exception("レッスンの予約可能上限に達しています。");
        }
        if (strcmp($this->plan, 'gold') === 0) {
            // return true;
            return;
        }

        // $this->plan === 'regular'
        if ($this->reservationCountThisMonth() > 5) {
            throw new Exception("今月の予約がプランの上限に達しています。");
        }
    }

    public function canReserve2(Lesson $lesson): bool
    {
        if ($lesson->isVacant()) {
            return true;
        }

        return false;
    }
}
