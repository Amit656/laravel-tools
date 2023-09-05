<?php
namespace App\Mixins;

class ResponseMixins
{
    /**
     * ! note
     * The mixin static method is used to load the methods of a mixin class 
     * instance into the target class as macro methods. The mixin method
     * accepts a class instance as it's first and only argument.
     */

    /**
     * This function will return error response in json format
     *
     * @return array
     */
    public function errorJson()
    {
        /**
         * This function will return error json response
         *
         * @param string $message
         * @param integer $code
         * @param array $data
         * @param array $metadata
         * @return array
         */
        return function (string $message, int $code = 500, $data = [], array $metadata = []) {
            return [
                'success' => false,
                'code' => $code,
                'message' => $message,
                'data' => [],
                'errors' => $data,
                'meta' => $metadata,
            ];
        };
    }

    /**
     * This function will return sucess response to user.
     *
     * @return array
     */
    public function sucessJson()
    {
        /**
         * This function will return sucess json response
         *
         * @param string $message
         * @param integer $code
         * @param array $data
         * @param array $metadata
         * @return array
         */
        return function (string $message, $data = [], int $code = 200, array $metadata = []) {
            return [
                'success' => true,
                'code' => $code,
                'message' => $message,
                'data' => $data,
                'meta' => $metadata,
            ];
        };
    }

    /**
     * This function will return datatable response.
     *
     * @return array
     */
    public function dataTableJson()
    {
        return function (array $dbData, $draw) {
            return [
                'draw' => (int) $draw,
                'recordsTotal' => $dbData['total'],
                'recordsFiltered' => $dbData['total'],
                'data' => $dbData['data'],
            ];
        };
    }
}
