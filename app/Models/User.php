<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'phone_verified_at' => 'datetime',
    ];

    public function currentbalance($retcurrency=false) {
        $defcur   = "USD";
        if ($retcurrency) { return Currency::where("ISOcode", $defcur)->first(); }
        $accounts = Account::where("user_id", $this->id)->get();
        $rates    = Storage::disk('public')->get('currency_rates.json');
        $rates    = json_decode($rates, true);
        $total    = 0;
        foreach ($accounts as $key => $acc) {
            $total += ($acc->currentbalance() * $rates[$acc->currency->ISOcode][$defcur]);
        }
        return $total;
    }

    public function name_represent() {
        if ($this->type == 0) {
            return $this->firstname." ".$this->lastname;
        }
        else { return $this->companyname." (".$this->firstname." ".$this->lastname.")"; }
    }

    public function id_represent() {
        return $this->id;
    }

    public function address_represent() {
        $address = empty($this->homeaddress) ? "NaN" : $this->homeaddress;
        $statepr = empty($this->stateprovince) ? "NaN" : $this->stateprovince;
        $city    = empty($this->city) ? "NaN" : $this->city;
        $zipcode = empty($this->zipcode) ? "NaN" : $this->zipcode;
        return $address.", ".$statepr.", ".$city." ".$zipcode;
    }

    public function userPhoneVerified()
    {
        return ! is_null($this->phone_verified_at);
    }

    // public function phoneVerifiedAt()
    // {
    //     return $this->forceFill([
    //         'phone_verified_at' => $this->freshTimestamp(),
    //     ])->save();
    // }

     public static function getpermissionGroups()
    {
        $permission_groups = DB::table('permissions')
            ->select('group_name as name')
            ->groupBy('group_name')
            ->get();
        return $permission_groups;
    }

    public static function getPermissionGroup()
    {
        return $permission_groups = DB::table('permissions')->select('group_name')->groupBy('group_name')->get();
    }
    public static function getpermissionsByGroupName($group_name)
    {
        $permissions = DB::table('permissions')
            ->select('name', 'id')
            ->where('group_name', $group_name)
            ->get();
        return $permissions;
    }

    public static function roleHasPermissions($role, $permissions)
    {
        $hasPermission = true;
        foreach ($permissions as $permission) {
            if (!$role->hasPermissionTo($permission->name)) {
                $hasPermission = false;
                return $hasPermission;
            }
        }
        return $hasPermission;
    }

    // Relation to transactions Model
    public function transactions() 
    {
        return $this->hasMany(Transaction::class, 'user_id', 'id');
    }
    
}
