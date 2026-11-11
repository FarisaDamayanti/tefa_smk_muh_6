<?php

namespace App\Services\Teacher;

use App\Enums\ModuleTypeEnum;
use App\Http\Requests\Teacher\StoreModuleRequest;
use App\Http\Requests\Teacher\UpdateModuleRequest;
use App\Models\Module;
use App\Models\Topic;

class ModuleService
{
    public function __construct() {}

    /**
     * mapping data for create data
     *
     * @param StoreModuleRequest $request
     * @param Topic $topic
     * @return array
     */
    public function store(StoreModuleRequest $request, Topic $topic): array
    {
        $data = $request->validated();
        $data['topic_id'] = $topic->id;

        if ($data['type'] === 'link') {
            $data['content'] = null;
        } else {
            $data['link'] = null;
            $data['link_type'] = ModuleTypeEnum::EDITOR->value;
        }

        return $data;
    }

    /**
     * mapping data for update data
     *
     * @param UpdateModuleRequest $request
     * @param Module $module
     * @return array
     */
    public function update(UpdateModuleRequest $request, Module $module): array
    {
        $data = $request->validated();

        if ($data['type'] === 'link') {
            $data['content'] = null;
        } else {
            $data['link'] = null;
            $data['link_type'] = ModuleTypeEnum::EDITOR->value;
        }

        return $data;
    }

    public function delete(): void {}
}
