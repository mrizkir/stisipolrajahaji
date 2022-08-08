<?php

namespace App\Models\System;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeederJobModel extends Model
{
	use HasFactory;
	
	/**
	 * nama tabel model ini.
	 *
	 * @var string
	*/
	protected $table = 'pe3_feeder_job';
	/**
	 * primary key tabel ini.
	 *
	 * @var string
	*/
	protected $primaryKey = 'id';
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	*/
	protected $fillable = [
		'id',
    'pid',
    'pname',
    'data_id',
    'isi_data',
    'desc',
    'prodi_id',    
    'kode_program_studi',    
    'id_semester',        
    'status',    
	];
	/**
	 * enable auto_increment.
	 *
	 * @var string
	*/
	public $incrementing = false;
	/**
	 * activated timestamps.
	 *
	 * @var string
	*/
	public $timestamps = true;
}

