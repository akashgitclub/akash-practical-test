<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MailLog extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'mail_logs';

    protected $fillable = [
        'user_id',
        'to_email',
        'cc_email',
        'subject',
        'send_status',
        'data',
        'response',
        'email_sent_at',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'email_sent_at' => 'datetime',
    ];

    /**
     * Relationships
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
