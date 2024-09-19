<?php

namespace Tests\Feature\Administration;

use App\Services\Administration\Role\RoleService;
use Database\Seeders\CreateSuperUserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class RoleServiceTest extends TestCase
{

    use RefreshDatabase;

    protected $seed = true;

    private function getTestData(): array {

        return [
            'roleId' => '1',
            'roleName' => 'TestRole name',
            'permissions' => [1,2],
        ];

    }

    public function setUp(): void
    {
        parent::setUp();

        $this->service = resolve(RoleService::class);
        $this->service->setData($this->getTestData());

    }

    public function testGetRole() {

        $this->seed([
            CreateSuperUserSeeder::class,

        ]);

        $list = $this->service->getList()->toArray(new Request());

        foreach ($list as $item) {
            $this->assertArrayHasKey('roleId', $item, 'Каждый элемент списка должен иметь свойство roleId.');
            $this->assertArrayHasKey('roleName', $item, 'Каждый элемент списка должен иметь свойство roleName.');
            $this->assertArrayHasKey('permissions', $item, 'Каждый элемент списка должен иметь свойство permissions.');
        }

    }


}
