<?php

namespace Laragrad\Models\Concerns;

use Laragrad\Support\PgHelper;

trait PgTypeCastable
{

    /**
     * Cast an attribute to a native PHP types.
     *
     * Added cast types: 'pg_array'
     *
     * @param  string   $key
     * @param  mixed    $value
     * @return mixed
     */
    protected function castAttribute($key, $value)
    {
        $ret = $value;
        if (!is_null($value)) {
            $caseType = $this->getCastType($key);
            $pgArrayCastType = false;
            switch ($caseType) {
                case 'pg_array':
                    $pgCastType = null;
                    break;
                case 'pg_text_array':
                    $pgCastType = 'string';
                    break;
                case 'pg_int_array':
                    $pgCastType = 'int';
                    break;
                case 'pg_numeric_array':
                    $pgCastType = 'float';
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
     * Added cast types: 'pg_array', 'pg_point', 'pg_custom_dd_mm'
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
                    $this->attributes[$key] = PgHelper::toPgArray($value);
                    break;
                default:
                    break;
            }
        }
        return $this;
    }
}
