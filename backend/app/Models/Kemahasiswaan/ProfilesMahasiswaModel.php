<?php

namespace App\Models\Kemahasiswaan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class ProfilesMahasiswaModel extends Model 
{
	use HasFactory;

	protected $guard_name = 'api';	
	/**
	 * nama tabel model ini.
	 *
	 * @var string
	 */
	protected $table = 'profiles_mahasiswa';
	/**
	 * primary key tabel ini.
	 *
	 * @var string
	 */
	protected $primaryKey = 'idprofile';	
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'idprofile', 
		'no_formulir',
		'email',
		'nim',
		'userpassword',
		'theme',
		'photo_profile',		
	];
	/**
	 * enable auto_increment.
	 *
	 * @var string
	 */
	public $incrementing = true;
	/**
	 * activated timestamps.
	 *
	 * @var string
	 */
	public $timestamps = false;	
}
