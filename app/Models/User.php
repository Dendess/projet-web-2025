<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    protected $fillable = [
        'last_name',
        'first_name',
        'email',
        'cohort_id',
        'average'
    ];
    // Utilisation des traits HasFactory et Notifiable pour gérer la création d'instances et les notifications.
    use HasFactory, Notifiable;

    /**
     * Attributs qui doivent être masqués lors de la sérialisation du modèle.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password', // Le mot de passe ne doit pas être inclus dans les données sérialisées.
    ];

    /**
     * Fonction permettant de définir les types des attributs pour la conversion automatique
     * des valeurs lors de l'enregistrement ou de la récupération des données.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime', // Définit que 'email_verified_at' est de type date/heure.
            'password' => 'hashed', // Définit que le mot de passe est haché.
        ];
    }

    /**
     * Retourne le nom complet de l'utilisateur (nom + prénom)
     *
     * @return string
     */
    public function getFullNameAttribute(): string
    {
        // Retourne le nom complet de l'utilisateur (nom + prénom).
        return $this->last_name . ' ' . $this->first_name;
    }

    /**
     * Retourne un nom court de l'utilisateur (prénom + initiale du nom)
     *
     * @return string
     */
    public function getShortNameAttribute(): string
    {
        // Retourne le prénom suivi de la première lettre du nom suivie d'un point.
        return $this->first_name . ' ' . $this->last_name[0] . '.';
    }

    /**
     * Fonction qui récupère l'école de l'utilisateur (un utilisateur peut avoir plusieurs écoles).
     *
     * @return (Model&object)|null
     */
    public function school()
    {
        // Retourne la première école à laquelle l'utilisateur est associé avec son rôle.
        return $this->belongsToMany(School::class, 'users_schools')
            ->withPivot('role') // Inclut le champ pivot 'role'.
            ->first(); // Ne retourne que la première association.
    }

    /**
     * Vérifie si l'utilisateur possède un rôle spécifique dans une école.
     *
     * @param string $role
     * @return bool
     */
    public function hasRole(string $role): bool
    {
        // Vérifie si l'utilisateur a un rôle donné dans l'association UserSchool.
        return $this->userSchools()->where('role', $role)->exists();
    }

    /**
     * Retourne la relation entre un utilisateur et ses écoles.
     */
    public function userSchools()
    {
        // Retourne la relation 'hasMany' entre l'utilisateur et l'association UserSchool.
        return $this->hasMany(UserSchool::class);
    }

    /**
     * Retourne la relation entre un utilisateur et sa cohorte.
     */
    public function cohort()
    {
        // Retourne la relation 'belongsTo' entre l'utilisateur et la cohorte.
        return $this->belongsTo(Cohort::class);
    }

    /**
     * Retourne les groupes auxquels l'utilisateur appartient.
     */
    public function groups()
    {
        // Retourne la relation 'belongsToMany' entre l'utilisateur et les groupes.
        return $this->belongsToMany(Group::class);
    }
}
