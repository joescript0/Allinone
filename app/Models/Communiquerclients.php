<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Communiquerclients extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'communiquerclients';

    protected $fillable = [
        'client_id',
        'client_nom',
        'client_email',
        'client_phone',
        'message',
        'mode',
        'filters',
        'payload',
        'statut',
        'erreur_message',
        'envoye_email',
        'envoye_sms',
        'envoye_whatsapp',
        'user_id',
        'envoye_le',
    ];

    protected $casts = [
        'filters'         => 'array',
        'payload'         => 'array',
        'envoye_email'    => 'boolean',
        'envoye_sms'      => 'boolean',
        'envoye_whatsapp' => 'boolean',
        'envoye_le'       => 'datetime',
    ];

    /**
     * ✅ Relation vers le client
     */
    public function client()
    {
        return $this->belongsTo(Clients::class, 'client_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}