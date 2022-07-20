<?php


namespace App\Virtual\Models;

use DateTime;

/**
 * @OA\Schema(
 *     title="User",
 *     description="User model",
 *     @OA\Xml(
 *         name="User"
 *     )
 * )
 */
class User
{

    /**
     * @OA\Property(
     *     title="ID",
     *     description="ID",
     *     format="int64",
     *     example=1
     * )
     *
     * @var integer
     */
    private $id;

    /**
     * @OA\Property(
     *      title="Name",
     *      description="Name of the new user",
     *      example="Name"
     * )
     *
     * @var string
     */
    public $name;

    /**
     * @OA\Property(
     *      title="Username",
     *      description="Username of the user",
     *      example="username"
     * )
     *
     * @var string
     */
    public $username;

    /**
     * @OA\Property(
     *      title="Email",
     *      description="Email of the user",
     *      example="email"
     * )
     *
     * @var string
     */
    public $email;


    /**
     * @OA\Property(
     *      title="Role",
     *      description="Role of the user",
     *      example="3"
     * )
     *
     * @var integer
     */
    public $role;

    /**
     * @OA\Property(
     *      title="Password",
     *      description="Password of the user",
     *      example="password"
     * )
     *
     * @var string
     */
    public $password;

    /**
     * @OA\Property(
     *     title="Created at",
     *     description="Created at",
     *     example="2020-01-27 17:50:45",
     *     format="datetime",
     *     type="string"
     * )
     *
     * @var DateTime
     */
    private $created_at;

    /**
     * @OA\Property(
     *     title="Updated at",
     *     description="Updated at",
     *     example="2020-01-27 17:50:45",
     *     format="datetime",
     *     type="string"
     * )
     *
     * @var DateTime
     */
    private $updated_at;

    /**
     * @OA\Property(
     *     title="Deleted at",
     *     description="Deleted at",
     *     example="2020-01-27 17:50:45",
     *     format="datetime",
     *     type="string"
     * )
     *
     * @var DateTime
     */
    private $deleted_at;
}
