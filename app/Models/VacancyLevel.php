<?php

namespace App\Models;

use DomainException;
use Illuminate\Database\Eloquent\Model;

class VacancyLevel extends Model
{
    private $vacantCount;
    //
    public function __construct(int $vacantCount)
    {
        $this->vacantCount = $vacantCount;
    }

    public function mark(): string
    {
        $marks = [
            'empty'=>'×',
            'few'=>'△',
            'enough'=>'◎',
        ];

        assert(isset($marks[$this->slug()]), new DomainException('invalid slug value.'));
        return $marks[$this->slug()];
    }

    public function slug(): string
    {
        if ($this->vacantCount === 0) {
            return "empty";
        }
        if ($this->vacantCount < 5) {
            return "few";
        }
        return "enough";
    }

    // public function __toString()
    // {
    //     return $this->mark();
    // }

}
