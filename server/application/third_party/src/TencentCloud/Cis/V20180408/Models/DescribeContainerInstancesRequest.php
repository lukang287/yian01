<?php
/*
 * Licensed to the Apache Software Foundation (ASF) under one
 * or more contributor license agreements.  See the NOTICE file
 * distributed with this work for additional information
 * regarding copyright ownership.  The ASF licenses this file
 * to you under the Apache License, Version 2.0 (the
 * "License"); you may not use this file except in compliance
 * with the License.  You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing,
 * software distributed under the License is distributed on an
 * "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY
 * KIND, either express or implied.  See the License for the
 * specific language governing permissions and limitations
 * under the License.
 */
namespace TencentCloud\Cis\V20180408\Models;
use TencentCloud\Common\AbstractModel;

/**
 * @method integer getOffset() 获取偏移量，默认为0
 * @method void setOffset(integer $Offset) 设置偏移量，默认为0
 * @method integer getLimit() 获取返回数量，默认为10
 * @method void setLimit(integer $Limit) 设置返回数量，默认为10
 * @method array getFilters() 获取过滤条件。
- Zone - String - 是否必填：否 -（过滤条件）按照可用区过滤。
- VpcId - String - 是否必填：否 -（过滤条件）按照VpcId过滤。
- InstanceName - String - 是否必填：否 -（过滤条件）按照容器实例名称做模糊查询。
 * @method void setFilters(array $Filters) 设置过滤条件。
- Zone - String - 是否必填：否 -（过滤条件）按照可用区过滤。
- VpcId - String - 是否必填：否 -（过滤条件）按照VpcId过滤。
- InstanceName - String - 是否必填：否 -（过滤条件）按照容器实例名称做模糊查询。
 */

/**
 *DescribeContainerInstances请求参数结构体
 */
class DescribeContainerInstancesRequest extends AbstractModel
{
    /**
     * @var integer 偏移量，默认为0
     */
    public $Offset;

    /**
     * @var integer 返回数量，默认为10
     */
    public $Limit;

    /**
     * @var array 过滤条件。
- Zone - String - 是否必填：否 -（过滤条件）按照可用区过滤。
- VpcId - String - 是否必填：否 -（过滤条件）按照VpcId过滤。
- InstanceName - String - 是否必填：否 -（过滤条件）按照容器实例名称做模糊查询。
     */
    public $Filters;
    /**
     * @param integer $Offset 偏移量，默认为0
     * @param integer $Limit 返回数量，默认为10
     * @param array $Filters 过滤条件。
- Zone - String - 是否必填：否 -（过滤条件）按照可用区过滤。
- VpcId - String - 是否必填：否 -（过滤条件）按照VpcId过滤。
- InstanceName - String - 是否必填：否 -（过滤条件）按照容器实例名称做模糊查询。
     */
    function __construct()
    {

    }
    /**
     * 内部实现，用户禁止调用
     */
    public function deserialize($param)
    {
        if ($param === null) {
            return;
        }
        if (array_key_exists("Offset",$param) and $param["Offset"] !== null) {
            $this->Offset = $param["Offset"];
        }

        if (array_key_exists("Limit",$param) and $param["Limit"] !== null) {
            $this->Limit = $param["Limit"];
        }

        if (array_key_exists("Filters",$param) and $param["Filters"] !== null) {
            $this->Filters = [];
            foreach ($param["Filters"] as $key => $value){
                $obj = new Filter();
                $obj->deserialize($value);
                array_push($this->Filters, $obj);
            }
        }
    }
}
