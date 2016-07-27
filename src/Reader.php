<?php
namespace Minhbang\ILib;

use Laracasts\Presenter\PresentableTrait;
use Minhbang\Ebook\Ebook;
use Minhbang\Enum\EnumContract;
use Minhbang\Enum\HasEnum;
use Minhbang\Enum\Enum;
use Minhbang\Kit\Extensions\Model;
use Minhbang\Kit\Traits\Model\DatetimeQuery;
use Minhbang\Kit\Traits\Model\SearchQuery;

/**
 * Class Reader
 *
 * @package Minhbang\ILib
 * @property integer $user_id
 * @property string $code
 * @property integer $security_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read string $security
 * @property-read string $user_name
 * @property-read string $user_username
 * @property-read \Minhbang\User\User $user
 * @property-read \Illuminate\Database\Eloquent\Collection|\Minhbang\Ebook\Ebook[] $ebooks
 * @property-read int $ebook_id
 * @property-read string $ebook_title
 * @property-read string $expires_at
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\ILib\Reader whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\ILib\Reader whereCode($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\ILib\Reader whereSecurityId($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\ILib\Reader whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\ILib\Reader whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\ILib\Reader forSelectize($ignore = null, $take = 10)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\ILib\Reader queryDefault()
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\ILib\Reader allowedEbook()
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\ILib\Reader withUser()
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Kit\Extensions\Model except($id = null)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Kit\Extensions\Model whereAttributes($attributes)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Kit\Extensions\Model findText($column, $text)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\ILib\Reader searchKeyword($keyword, $columns = null)
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
 * @mixin \Eloquent
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
    protected $primaryKey = 'user_id';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(config('auth.providers.users.model'));
    }

    /**
     * Danh sách ebooks mà reader đã được gán quyền đọc tạm thời
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function ebooks()
    {
        //TODO: Thêm kiểm tra điều kiện 'Thời hạn' => Lấy các ebooks còn hiệu lực
        return $this->belongsToMany(Ebook::class);
    }

    /**
     * Reader có thể đọc $ebook khi:
     * - Quyền đọc >= Mức bảo mật của $ebook
     * - Hoặc được gán quyền đọc tạm thời đối với $ebook
     * - Hoặc người của Thư viện
     *
     * @param \Minhbang\Ebook\Ebook $ebook
     *
     * @return bool
     */
    public function canRead($ebook)
    {
        return Enum::compare($this->security_id, $ebook->security_id, '>=') ||
        in_array($ebook->id, $this->ebooks->pluck('id')) || user()->hasRole('tv.*');
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
                "{$this->table}.user_id as id",
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
            ->join('ebook_reader', "{$this->table}.user_id", '=', 'ebook_reader.reader_id')
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