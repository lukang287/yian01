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
namespace TencentCloud\Batch\V20170312\Models;
use TencentCloud\Common\AbstractModel;

/**
 * @method string getSourcePath() 获取源端路径
 * @method void setSourcePath(string $SourcePath) 设置源端路径
 * @method string getDestinationPath() 获取目的端路径
 * @method void setDestinationPath(string $DestinationPath) 设置目的端路径
 */

/**
 *输出映射
 */
class OutputMapping extends AbstractModel
{
    /**
     * @var string 源端路径
     */
    public $SourcePath;

    /**
     * @var string 目的端路径
     */
    public $DestinationPath;
    /**
     * @param string $SourcePath 源端路径
     * @param string $DestinationPath 目的端路径
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
        if (array_key_exists("SourcePath",$param) and $param["SourcePath"] !== null) {
            $this->SourcePath = $param["SourcePath"];
        }

        if (array_key_exists("DestinationPath",$param) and $param["DestinationPath"] !== null) {
            $this->DestinationPath = $param["DestinationPath"];
        }
    }
}
