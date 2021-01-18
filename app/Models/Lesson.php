<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{

    // 主 -> 従
    public function reservations() {
        return $this->hasMany('App\Models\Reservation');
        // return $this->hasMany(Reservation::class);
    }

    //
    public function getVacancyLevelAttribute(): VacancyLevel
    {
        return new VacancyLevel($this->getVacantCount());
    }

    public function getVacantCount(): int
    {
        // return 0;
        return $this->capacity - $this->reservations()->count();
    }

}
