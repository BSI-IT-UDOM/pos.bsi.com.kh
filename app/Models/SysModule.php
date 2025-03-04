<?php

namespace App\Models;

use App\Models\Module;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SysModule extends Model
{
    use HasFactory;
    protected $table = 'sys_module';
    public $timestamps = false;
    protected $primaryKey = 'SM_id';
    public $incrementing = true;
    protected $keyType = 'int'; 
    protected $fillable = [
        'SM_name',
        'SM_label',
        'SM_image',
        'status'
    ];

    public function modules()
    {
        return $this->hasMany(Module::class, 'SM_id', 'SM_id');
    }
}
