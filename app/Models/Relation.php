<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Relation extends Model
{
    use HasFactory;

    protected $table = 'relations';

    protected $fillable = [
        'person_id',
        'organization_id'
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function person()
    {
        return $this->belongsTo(Person::class, 'person_id');
    }
}
