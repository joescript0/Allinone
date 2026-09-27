<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rappelscredits extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'rappelscredits';

    protected $fillable = [
        'factureass_id', 'client_id', 'client_nom',
        'facture_numero', 'credit_usd', 'credit_cdf', 'devise',
        'message', 'mode', 'filters', 'payload',
        'statut', 'erreur_message',
        'envoye_email', 'envoye_sms', 'envoye_whatsapp',
        'user_id', 'envoye_le',
    ];

    protected $casts = [
        'filters'          => 'array',
        'payload'          => 'array',
        'envoye_email'     => 'boolean',
        'envoye_sms'       => 'boolean',
        'envoye_whatsapp'  => 'boolean',
        'envoye_le'        => 'datetime',
        'credit_usd'       => 'decimal:2',
        'credit_cdf'       => 'decimal:2',
    ];

    /**
     * ✅ Relation vers la facture (table factureasses)
     */
    public function factureass()
    {
        return $this->belongsTo(Factureasses::class, 'factureass_id');
    }

    public function client()
    {
        return $this->belongsTo(Clients::class, 'client_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}