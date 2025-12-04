<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'customer_id',
        'supplier_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function factory()
    {
        return $this->hasOne(Factory::class, 'user_id');
    }

    public function retailer()
    {
        return $this->hasOne(Retailer::class, 'user_id');
    }

    public function driver()
    {
        return $this->hasOne(Driver::class, 'user_id');
    }

    public function customer()
    {
        // customers table stores user_id -> users.id
        return $this->hasOne(Customer::class, 'user_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function hasRole($role)
    {
        // Accept Role model, role name string, or array of role names
        if ($role instanceof Role) {
            return $this->role_id && $this->role_id === $role->id;
        }

        if (is_array($role)) {
            foreach ($role as $r) {
                if ($this->hasRole($r)) {
                    return true;
                }
            }

            return false;
        }

        // $role is expected to be a string name
        // If relation loaded or returns a Role model, compare by name
        $related = $this->getRelationValue('role');
        if ($related instanceof Role) {
            return $related->name === $role;
        }

        // If a 'role' attribute exists directly on the model (string), compare it
        if (array_key_exists('role', $this->attributes) && is_string($this->attributes['role'])) {
            return $this->attributes['role'] === $role;
        }

        // Fallback: if we have role_id, try to resolve it
        if ($this->role_id) {
            $roleModel = Role::find($this->role_id);
            return $roleModel && $roleModel->name === $role;
        }

        return false;
    }
}
