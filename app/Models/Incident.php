<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Incident extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'commune_id',
        'incident_type_id',
        'latitude',
        'longitude',
        'is_resolved',
    ];

    public function incidentType()
    {
        return $this->belongsTo(IncidentType::class);
    }

    public function commune()
    {
        return $this->belongsTo(Commune::class);
    }
}
