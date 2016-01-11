<?php
namespace Minhbang\ILib;

use Laracasts\Presenter\PresentableTrait;
use Minhbang\Ebook\Ebook;
use Minhbang\Enum\EnumContract;
use Minhbang\Enum\HasEnum;
use Minhbang\LaravelKit\Extensions\Model;
use Minhbang\LaravelKit\Traits\Model\DatetimeQuery;
use Minhbang\LaravelKit\Traits\Model\SearchQuery;

/**
 * Class Reader
 *
 * @package Minhbang\ILib
 * @property integer $id
 * @property string $code
 * @property integer $security_id
 * @property integer $user_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read string $security
 * @property-read string $user_name
 * @property-read string $user_username
 * @property-read string $expires_at
 * @property-read integer $ebook_id
 * @property-read string $ebook_title
 * @property-read \Minhbang\LaravelUser\User $user
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\ILib\Reader queryDefault()
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\ILib\Reader withUser()
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelKit\Extensions\Model except($id = null)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelKit\Extensions\Model findText($column, $text)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\ILib\Reader searchWhere($column, $operator = '=', $fn = null)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\ILib\Reader searchWhereIn($column, $fn)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\ILib\Reader searchWhereBetween($column, $fn = null)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\ILib\Reader searchWhereInDependent($column, $column_dependent, $fn, $empty = [])
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\ILib\Reader orderCreated($direction = 'desc')
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\ILib\Reader orderUpdated($direction = 'desc')
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\ILib\Reader period($start = null, $end = null, $field = 'created_at', $end_if_day = false, $is_month = false)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\ILib\Reader today($field = 'created_at')
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\ILib\Reader yesterday($same_time = false, $field = 'created_at')
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\ILib\Reader thisWeek($field = 'created_at')
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\ILib\Reader thisMonth($field = 'created_at')
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\ILib\Reader withEnumTitles()
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\ILib\Reader forSelectize($ignore = null, $take = 10)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\ILib\Reader allowedEbook()
 * @property-read \Illuminate\Database\Eloquent\Collection|\Minhbang\Ebook\Ebook[] $ebooks
 */
class Reader extends Model implements EnumContract
{
    use SearchQuery;
    use PresentableTrait;
    use DatetimeQuery;
    use HasEnum;
    protected $presenter = ReaderPresenter::class;
    protected $table = 'readers';
    protected $fillable = ['code', 'security_id', 'user_id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(config('auth.providers.users.model'));
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function ebooks()
    {
        return $this->belongsToMany(Ebook::class);
    }

    /**
     * Lấy $take reader phục vụ selectize reader
     * !! KHÔNG dùng với withUser()
     *
     * @param \Illuminate\Database\Query\Builder|static $query
     * @param mixed|null $ignore
     * @param int $take
     *
     * @return \Illuminate\Database\Query\Builder|static
     */
    public function scopeForSelectize($query, $ignore = null, $take = 10)
    {
        return $query->except($ignore)
            ->leftJoin('users', "{$this->table}.user_id", '=', 'users.id')
            ->leftJoin('user_groups', 'user_groups.id', '=', 'users.group_id')
            ->addSelect([
                "users.name",
                "users.username",
                "user_groups.full_name as group_name",
            ])
            ->take($take);
    }

    /**
     * @param \Illuminate\Database\Query\Builder|static $query
     *
     * @return \Illuminate\Database\Query\Builder|static
     */
    public function scopeQueryDefault($query)
    {
        return $query->select("{$this->table}.*");
    }

    /**
     * @param \Illuminate\Database\Query\Builder|static $query
     *
     * @return \Illuminate\Database\Query\Builder|static
     */
    public function scopeAllowedEbook($query)
    {
        return $query
            ->join('ebook_reader', "{$this->table}.id", '=', 'ebook_reader.reader_id')
            ->join('ebooks', "ebooks.id", '=', 'ebook_reader.ebook_id')
            ->addSelect(['ebook_reader.expires_at', 'ebook_reader.ebook_id', 'ebooks.title as ebook_title'])
            ->orderBy('ebook_reader.created_at');
    }

    /**
     * Load thông tin user
     *
     * @param \Illuminate\Database\Query\Builder $query
     *
     * @return \Illuminate\Database\Query\Builder|static
     */
    public function scopeWithUser($query)
    {
        $query->leftJoin('users', "{$this->table}.user_id", '=', "users.id")
            ->addSelect(
                'users.name as user_name',
                'users.username as user_username'
            );

        return $query;
    }

    /**
     * @return string
     */
    public function enumGroup()
    {
        return 'ebook';
    }

    /**
     * @return string
     */
    public function enumGroupTitle()
    {
        return trans('ebook::common.ebook');
    }

    /**
     * Các attributes có giá trị là các Enum
     *
     * @return string
     */
    protected function enumAttributes()
    {
        return [
            'security_id' => trans('ebook::common.security_id'),
        ];
    }

    /**
     * @return array
     */
    protected function enumGuarded()
    {
        return ['security_id'];
    }
}