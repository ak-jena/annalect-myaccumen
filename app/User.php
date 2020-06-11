<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['username', 'email', 'password','name','blocked','pagination','num_cutoff','site_skin','menubar_collapse','can_viewas','can_manage_user','role_id'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];
    
    public $newPassword="********";


    /**
     * Agencies that belong to the user
     * Accuen users will belong to multiple agencies
     * All other users will belong to one agency
     */
    public function agencies()
    {
        return $this->belongsToMany('App\Agency', 'agencies_users')->withTimestamps();
    }

    /**
     * Role of this user
     */
    public function role()
    {
        return $this->belongsTo('App\Role');
    }

    /**
     * Get the logs for this user.
     */
    public function logs()
    {
        return $this->hasMany('App\Log');
    }

    /**
     * Get the comments for this user.
     */
    public function comments()
    {
        return $this->hasMany('App\Comment');
    }

    /**
     * Get users within this users agency (or all users if accuen user)
     */
    public function getUsersWithinAgencyAttribute()
    {
        // get the agencies the user belongs to
        $agencies = $this->agencies()->get();

//        $users = array();

        $users = new Collection();
        foreach ($agencies as $agency){
//            var_dump($agency->name);die;
            foreach ($agency->users as $user) {
                $users->add($user);
            }
        }

        return $users;
    }

    /**
     * Get all clients within this users agency (or all clients if accuen user)
     *
     * @return Collection
     * @author Saeed Bhuta
     * @version 20170517
     */
    private function getClientsInAgencyAttribute()
    {
        // get the agencies the user belongs to
        $agencies = $this->agencies()->get();

        $clients = new Collection();
        foreach ($agencies as $agency){
            foreach ($agency->clients as $client) {
                $clients->add($client);
            }
        }

        return $clients;
    }

    /**
     * Get client ids within this users agency (or all clients if accuen user)
     *
     * @return Array
     *
     * @author Saeed Bhuta
     * @version 20170607
     */
    public function getClientsIdsAttribute()
    {
        // get the agencies the user belongs to
        $agencies = $this->agencies()->get();

        $client_ids = array();
        foreach ($agencies as $agency){
            foreach ($agency->clients as $client) {
                $client_ids[] = $client->id;
            }
        }

        return $client_ids;
    }


    /**
     * Get the targeting grids uploaded by this user
     */
    public function grids()
    {
        return $this->hasMany('App\Grid');
    }

    /**
     * Get the io uploaded by this user
     */
    public function dspBudgets()
    {
        return $this->hasMany('App\DspBudget');
    }

    /**
     * Get the creative tags uploaded by this user
     */
    public function tags()
    {
        return $this->hasMany('App\Tag');
    }

    /**
     * Get the briefs created by this user
     */
    public function briefs()
    {
        return $this->hasMany('App\Brief');
    }

    /**
     * The clients that assigned to the user.
     */
    public function clients()
    {
        return $this->belongsToMany('App\Client', 'clients_users')->withTimestamps();
    }

    /**
     * Get clients that the user is allowed to access:
     * i) agency user - clients that they are assigned to
     * ii) all other users - all the clients that belong to the agencies they're assigned to
     *
     * @return Collection
     *
     * @author Saeed Bhuta
     * @version 20170921
     */
    public function getPermittedClientsAttribute(){

        // get users role
        $role = $this->role()->get()->first();

        // check which role they have and return relevant clients
        if($role->id == Role::AGENCY_USER){
            return $this->clients()->get();
        }else{
            return $this->getClientsInAgencyAttribute();
        }

    }

    public function targetingGrids()
    {
        return $this->hasMany('App\TargetingGrid');
    }

}
