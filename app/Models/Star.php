<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Star extends Model
{
    use HasFactory;

    protected $fillable = [
        'img_path',
        'description',
    ];

    protected $table = "stars";

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->first_name;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->last_name;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getImgPath(): string
    {
        return $this->img_path;
    }

    /**
     * @param int $id
     *
     * @return Star
     * @throws Exception
     */
    public static function getFromId(int $id): Star
    {
        /** @var Star $star */
        $star = Star::all()->where('id', '=', $id)->first();
        if(empty($star)){
            throw new Exception("Star not found from id $id");
        }
        return $star;
    }
}
