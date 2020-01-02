<?php

namespace App;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Sorts
 *
 * @property int $id
 * @property int $projectId
 * @property string|null $backlog
 * @property string|null $progress
 * @property string|null $test
 * @property string|null $done
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|Sorts newModelQuery()
 * @method static Builder|Sorts newQuery()
 * @method static Builder|Sorts query()
 * @method static Builder|Sorts whereBacklog($value)
 * @method static Builder|Sorts whereCreatedAt($value)
 * @method static Builder|Sorts whereDone($value)
 * @method static Builder|Sorts whereId($value)
 * @method static Builder|Sorts whereProgress($value)
 * @method static Builder|Sorts whereProjectId($value)
 * @method static Builder|Sorts whereTest($value)
 * @method static Builder|Sorts whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Sorts extends Model
{
    protected $table = 'sorts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'projectId',
        'backlog',
        'progress',
        'test',
        'done'
    ];
}
