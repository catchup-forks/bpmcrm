<?php

namespace ProcessMaker\Models\Crm;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Relation
 * @package App\Models\Crm
 * @version November 8, 2018, 12:29 pm UTC
 *
 * @property \ProcessMaker\Models\Crm\Company company
 * @property \Illuminate\Database\Eloquent\Collection CalendarEvent
 * @property \Illuminate\Database\Eloquent\Collection leads
 * @property \Illuminate\Database\Eloquent\Collection Prospect
 * @property \Illuminate\Database\Eloquent\Collection RelationsEmailaddress
 * @property \Illuminate\Database\Eloquent\Collection Suspect
 * @property \Illuminate\Database\Eloquent\Collection tagsLinks
 * @property \Illuminate\Database\Eloquent\Collection ticketsThreads
 * @property \Illuminate\Database\Eloquent\Collection TicketsTimetrack
 * @property integer companyid
 * @property string relationname
 * @property string slug
 */
class Relation extends Model
{
    use SoftDeletes;

    public $table = 'relations';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'company_id',
        'relationname',
        'slug'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'company_id' => 'integer',
        'relationname' => 'string',
        'slug' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function company()
    {
        return $this->belongsTo(\ProcessMaker\Models\Crm\Company::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function calendarevents()
    {
        return $this->hasMany(\ProcessMaker\Models\Crm\CalendarEvent::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function prospect()
    {
        return $this->belongsTo(\ProcessMaker\Models\Crm\Prospect::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function emailaddresses()
    {
        return $this->hasMany(\ProcessMaker\Models\Crm\RelationsEmailaddress::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function suspect()
    {
        return $this->belongsTo(\ProcessMaker\Models\Crm\Suspect::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function ticketstimetracks()
    {
        return $this->hasMany(\ProcessMaker\Models\Crm\TicketsTimetrack::class);
    }
}
