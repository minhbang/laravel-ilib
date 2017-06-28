<?php

namespace Minhbang\ILib\Reader;

use Carbon\Carbon;
use Laracasts\Presenter\PresentableTrait;
use Minhbang\Ebook\Ebook;
use Minhbang\Enum\UseEnum;
use Minhbang\Enum\EnumModel;
use Minhbang\Kit\Extensions\Model;
use Minhbang\Kit\Traits\Model\DatetimeQuery;
use Minhbang\Kit\Traits\Model\SearchQuery;
use DB;

/**
 * Class Reader
 *
 * @package Minhbang\ILib
 * @property int $user_id
 * @property string $code
 * @property int $security_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Minhbang\Ebook\Ebook[] $ebooks
 * @property-read \Minhbang\User\User $user
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\ILib\Reader\Reader allowedEbook()
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Kit\Extensions\Model except( $ids )
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Kit\Extensions\Model findText( $column, $text )
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\ILib\Reader\Reader forSelectize( $ignore = null, $take = 10 )
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\ILib\Reader\Reader orderCreated( $direction = 'desc' )
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\ILib\Reader\Reader orderUpdated( $direction = 'desc' )
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\ILib\Reader\Reader period( $start = null, $end = null, $field = 'created_at', $end_if_day = false, $is_month = false )
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\ILib\Reader\Reader queryDefault()
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\ILib\Reader\Reader searchKeyword( $keyword, $columns = null )
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\ILib\Reader\Reader searchWhere( $column, $operator = '=', $fn = null )
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\ILib\Reader\Reader searchWhereBetween( $column, $fn = null )
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\ILib\Reader\Reader searchWhereIn( $column, $fn )
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\ILib\Reader\Reader searchWhereInDependent( $column, $column_dependent, $fn, $empty = [] )
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\ILib\Reader\Reader thisMonth( $field = 'created_at' )
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\ILib\Reader\Reader thisWeek( $field = 'created_at' )
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\ILib\Reader\Reader today( $field = 'created_at' )
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Kit\Extensions\Model whereAttributes( $attributes )
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\ILib\Reader\Reader whereCode( $value )
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\ILib\Reader\Reader whereCreatedAt( $value )
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\ILib\Reader\Reader whereSecurityId( $value )
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\ILib\Reader\Reader whereUpdatedAt( $value )
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\ILib\Reader\Reader whereUserId( $value )
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\ILib\Reader\Reader withEnumTitles()
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\ILib\Reader\Reader withUser()
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\ILib\Reader\Reader yesterday( $same_time = false, $field = 'created_at' )
 * @mixin \Eloquent
 * Enums ---
 * @property-read string $security_title
 * @property-read string $security_params
 */
class Reader extends Model {
    use SearchQuery;
    use PresentableTrait;
    use DatetimeQuery;
    use UseEnum;
    protected $presenter = ReaderPresenter::class;
    protected $table = 'readers';
    protected $fillable = [ 'code', 'security_id', 'user_id' ];
    protected $primaryKey = 'user_id';

    /**
     * Các thuộc tính enums được bảo vệ, chỉ chọn, không cho phép tạo mới
     *
     * @var array
     */
    protected $enumGuarded = [ 'security_id' ];

    /**
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null|static|static[]
     */
    public static function current() {
        return self::find( user( 'id' ) );
    }

    /**
     * @param string $key
     * @param string $attribute
     *
     * @return array
     */
    public static function allSecurity( $key = 'id', $attribute = 'title' ) {
        $securities = self::loadEnums( $key, $attribute )['securities'];
        if ( ! user_is( 'thu_vien.phu_trach' ) ) {
            $securities = [ key( $securities ) => reset( $securities ) ];
        }
        return $securities;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user() {
        return $this->belongsTo( config( 'auth.providers.users.model' ) );
    }

    /**
     * Danh sách ebooks mà reader đã được gán quyền đọc tạm thời
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function ebooks() {
        return $this->belongsToMany( Ebook::class, 'ebook_reader', 'reader_id', 'ebook_id' )
                    ->withTimestamps()
                    ->wherePivot( 'expires_at', '>=', Carbon::now() );
    }

    /**
     * Xóa các quyền đã hết hạn
     */
    public static function removeExpired() {
        DB::table( 'ebook_reader' )->where( 'expires_at', '<', Carbon::now() )->delete();
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
    public function canRead( $ebook ) {
        return EnumModel::compare( $this->security_id, $ebook->security_id, '>=' ) ||
               in_array( $ebook->id, $this->ebooks->pluck( 'id' )->all() ) || authority()->user()->hasRole( 'thu_vien.*' );
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
    public function scopeForSelectize( $query, $ignore = null, $take = 10 ) {
        return $query->except( $ignore )
                     ->leftJoin( 'users', "{$this->table}.user_id", '=', 'users.id' )
                     ->leftJoin( 'user_groups', 'user_groups.id', '=', 'users.group_id' )
                     ->addSelect( [
                         "{$this->table}.user_id as id",
                         "users.name",
                         "users.username",
                         "user_groups.full_name as group_name",
                     ] )
                     ->take( $take );
    }

    /**
     * @param \Illuminate\Database\Query\Builder|static $query
     *
     * @return \Illuminate\Database\Query\Builder|static
     */
    public function scopeQueryDefault( $query ) {
        return $query->select( "{$this->table}.*" );
    }

    /**
     * @param \Illuminate\Database\Query\Builder|static $query
     *
     * @return \Illuminate\Database\Query\Builder|static
     */
    public function scopeAllowedEbook( $query ) {
        return $query
            ->join( 'ebook_reader', "{$this->table}.user_id", '=', 'ebook_reader.reader_id' )
            ->join( 'ebooks', "ebooks.id", '=', 'ebook_reader.ebook_id' )
            ->addSelect( [ 'ebook_reader.expires_at', 'ebook_reader.ebook_id', 'ebooks.title as ebook_title' ] )
            ->orderBy( 'ebook_reader.created_at' );
    }

    /**
     * Load thông tin user
     *
     * @param \Illuminate\Database\Query\Builder $query
     *
     * @return \Illuminate\Database\Query\Builder|static
     */
    public function scopeWithUser( $query ) {
        $query->leftJoin( 'users', "{$this->table}.user_id", '=', "users.id" )
              ->addSelect(
                  'users.name as user_name',
                  'users.username as user_username'
              );

        return $query;
    }
}