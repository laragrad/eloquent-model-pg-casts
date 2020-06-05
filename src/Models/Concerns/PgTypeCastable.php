<?php

namespace Laragrad\Models\Concerns;

use Laragrad\Support\PgHelper;

trait PgTypeCastable
{

    /**
     * Cast an attribute to a native PHP types.
     *
     * Added cast types: 'pg_array', 'pg_text_array', 'pg_uuid_array', 'pg_int_array', 'pg_numeric_array'
     *
     * @param  string   $key
     * @param  mixed    $value
     * @return mixed
     */
    protected function castAttribute($key, $value)
    {
        $ret = $value;
        if (!is_null($value)) {
            $castType = $this->getCastType($key);
            $pgArrayCastType = false;
            switch ($castType) {
                case 'pg_array':
                    $pgArrayCastType = null;
                    break;
                case 'pg_uuid_array':
                case 'pg_text_array':
                    $pgArrayCastType = 'string';
                    break;
                case 'pg_int_array':
                    $pgArrayCastType = 'int';
                    break;
                case 'pg_numeric_array':
                    $pgArrayCastType = 'float';
                    break;
            }

            if ($pgArrayCastType !== false) {
                return PgHelper::fromPgArray($value, $pgArrayCastType);
            }

            return parent::castAttribute($key, $value);
        }
        return $value;
    }

    /**
     * Cast an attribute from native PHP types to custom and PjstgreSQL types.
     *
     * Added cast types: 'pg_array', 'pg_text_array', 'pg_uuid_array', 'pg_int_array', 'pg_numeric_array'
     *
     * @param  string $key
     * @param  mixed  $value
     * @return mixed
     */
    public function setAttribute($key, $value)
    {
        parent::setAttribute($key, $value);

        if (isset($this->casts[$key])) {
            switch ($this->getCastType($key)) {
                case 'pg_array':
                case 'pg_uuid_array':
                case 'pg_text_array':
                case 'pg_int_array':
                case 'pg_numeric_array':
                    if (!is_null($value)) {
                        $this->attributes[$key] = PgHelper::toPgArray($value);
                    }
                    break;
                default:
                    break;
            }
        }
        return $this;
    }
}
