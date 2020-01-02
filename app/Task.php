<?php

namespace App;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Task
 *
 * @property int $id
 * @property int $projectId
 * @property int $userId
 * @property int $parentId,
 * @property string|null $title
 * @property string|null $description
 * @property string|null $childSort
 * @property int|null $childCount
 * @property int|null $childDoneCount
 * @property string|null $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|Task newModelQuery()
 * @method static Builder|Task newQuery()
 * @method static Builder|Task query()
 * @method static Builder|Task whereChildCount($value)
 * @method static Builder|Task whereChildDoneCount($value)
 * @method static Builder|Task whereChildSort($value)
 * @method static Builder|Task whereCreatedAt($value)
 * @method static Builder|Task whereDescription($value)
 * @method static Builder|Task whereId($value)
 * @method static Builder|Task whereProjectId($value)
 * @method static Builder|Task whereStatus($value)
 * @method static Builder|Task whereTitle($value)
 * @method static Builder|Task whereUpdatedAt($value)
 * @method static Builder|Task whereUserId($value)
 * @mixin Eloquent
 */
class Task extends Model
{
    protected $table = 'task';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'projectId',
        'userId',
        'parentId',
        'title',
        'description',
        'childSort',
        'childCount',
        'childDoneCount',
        'status',
    ];

}
