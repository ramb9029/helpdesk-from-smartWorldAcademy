<?php

namespace App\Models;

use App\Models\StatusExecution;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Http\JsonResponse;

/**
 * Класс заявки
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property string $file
 * @property string $priority
 * @property string $estimatedDueDate
 * @property array $topics
 * @property int $statusExecution_id
 * @property int $executor_user_id
 * @property int $client_user_id
 * @property bool $access
 * @property string|null $action
 *
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property StatusExecution $statusExecution
 * @property Topic $topic
 * @property User $user
 *
 * @property Review[] $reviews
 * @property Message[] $messages
 *
 * Class Orders
 * @package App\Models
 */
class Order extends Model
{
    use HasFactory;

    public const FILE_DIR = 'orders/';
    const STATUS_EXECUTION_ARCHIVE = 1;
    const STATUS_EXECUTION_NEW = 2;
    const STATUS_EXECUTION_WORK = 3;
    const STATUS_EXECUTION_COMPLETE = 4;

    protected $table = 'orders';

    protected $perPage = 10;

    public const RELATIONS = [
        'reviews',
        'messages',
        // 'topic',
        'statusExecution',
        'executorUsers',
        'clientUsers',
    ];

    /**
     * Свойства для отображения значений промежуточных таблиц
     * на стороне фронта
     */
    public string $statusExecution;
    public array $topics = array();
    public string $clientUser;
    public array $executorUsers = array();
    public array $checkListOrders = array();

    public const RELATIONS_TRANSFORMERS = [
//        'reviews'  => \App\Converters\ReviewConvertor::class,
//        'messages'  => \App\Converters\MessageConvertor::class,
//        'topic'  => \App\Converters\TopicConvertor::class,
//        'statusExecution'  => \App\Converters\StatusExecutionConvertor::class,
//        'executorUsers'     => \App\Converters\UserConvertor::class,
//        'clientUsers'     => \App\Converters\UserConvertor::class,
    ];

    /**
     * Передаваемые в бд строки
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'file',
        'statusExecution_id',
        'priority',
        // 'topic_id',
        'estimatedDueDate',
        //'executor_user_id',
        'client_user_id',
        'access',
        'action',
    ];

    /**
     * Защищенные данные
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * Отвечает за то, как данные преобразуются для записи бд
     *
     * @var array
     */
    protected $casts = [];

    public $timestamps = true;

    /**
     *
     * @return HasMany
     */
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    public function checkLists(): HasMany
    {
        return $this->hasMany(CheckListOrder::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     *
     * @return BelongsTo
     */
    public function statusExecution(): BelongsTo
    {
        return $this->belongsTo(StatusExecution::class, 'id', 'statusExecution_id');
    }

    public function clientUsers(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id', 'client_user_id');
    }

    /**
     *
     * @return BelongsToMany
     */
    public function topic(): BelongsToMany
    {
        return $this->belongsToMany(Topic::class, 'order_topics', 'order_id', 'topic_id');
    }

    public function executorUser(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'order_executor', 'order_id', 'user_id');
    }
}
