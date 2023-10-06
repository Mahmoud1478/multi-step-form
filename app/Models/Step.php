<?php

namespace App\Models;

use App\Enums\Step\StateEnum;
use App\Enums\User\RoleEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Step extends Model
{
    use HasFactory;
    protected $fillable =[
        'data','number','state','user_id',
    ];


    protected $casts = [
        'state' => StateEnum::class,
        'data' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }


    function subject():?Subject
    {
        return Subject::find($this->data['subject_id']??0);
    }

    function role(): string
    {
        return RoleEnum::from($this->data['role'])->toString();
    }

    function count_(): ?string
    {
        return $this->data['count']?? null;
    }

    function teacher():?User
    {
        return User::find($this->data['teacher_id']?? 0);
    }

}
