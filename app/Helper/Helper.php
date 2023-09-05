<?php

namespace App\Helper;

use App\Model\SetupType;
use App\Model\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Aws\S3\S3Client;
use Aws\Exception\AwsException;
use Aws\Exception\S3Exception;
use Illuminate\Support\Str;

class Helper
{
    /**
     * This function will handle data tables request and response
     *
     * @param object $getRequest
     * @param string $defaultSortBy
     * @return object
     */
    public static function handleDataTableQuery($getRequest, $defaultSortBy = "id")
    {
        $columns = $getRequest->input('columns');
        $requestColumn = [];

        $pageStart = $getRequest->input('start');
        $pageLength = $getRequest->input('length');
        /**
         * To get page number
         */
        $pageNumber = abs($pageStart / $pageLength);

        foreach ($columns as $key => $value) {
            /**
             * Create column array for get value
             */
            $requestColumn[] = $value['data'];
        }
        $sortById = (int) $getRequest->input('order.0.column');
        /**
         * order by not exist then pass defaultSortBy order by
         */
        $dbSortById = ($sortById) ? $requestColumn[$sortById] : $defaultSortBy;
        $orderBy = ($sortById) ? $getRequest->input('order.0.dir') : 'DESC';
        /**
         * add extra data in request
         */
        $getRequest->merge(['sort_by' => $dbSortById, 'order_by' => $orderBy, 'page' => $pageNumber+1]);
    }
}
