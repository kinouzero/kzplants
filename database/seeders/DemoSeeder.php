<?php

namespace Database\Seeders;

use App\Models\Checklist;
use App\Models\Dashboard;
use App\Models\Item;
use App\Models\Plant;
use App\Models\Preference;
use App\Models\Property;
use App\Models\Role;
use App\Models\Stage;
use App\Models\Statut;
use App\Models\Strain;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::firstOrCreate([
            'email' => env('SEEDER_USER_MAIL', 'email@example.com'),
        ], [
            'name' => env('SEEDER_USER_NAME', 'User 1'),
            'password' => Hash::make(env('SEEDER_USER_PWD', 'user')),
        ]);

        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'user']);
        $roles = Role::all();
        $user->roles()->sync($roles->pluck('id'));

        Preference::firstOrCreate(['key' => 'lang'], ['name' => 'Language', 'type' => 'checklist', 'options' => json_encode(['props' => ['en' => 'English', 'fr' => 'French']])]);
        Preference::firstOrCreate(['key' => 'theme'], ['name' => 'Dark mode', 'type' => 'checklist', 'options' => json_encode(['props' => ['dark' => 'dark', 'light' => 'light']])]);
        Preference::firstOrCreate(['key' => 'table-length'], ['name' => 'Table length', 'type' => 'checklist', 'options' => json_encode(['props' => [10 => 10, 25 => 25, 50 => 50, 100 => 100]])]);
        Preference::firstOrCreate(['key' => 'timezone'], ['name' => 'Timezone', 'type' => 'text']);
        Preference::firstOrCreate(['key' => 'flush'], ['name' => 'Flush weeks', 'type' => 'number']);
        Preference::firstOrCreate(['key' => 'interval-watering-chemical'], ['name' => 'Interval between chemical', 'type' => 'number']);
        Preference::firstOrCreate(['key' => 'interval-watering-water'], ['name' => 'Interval between water', 'type' => 'number']);

        $statutNew = Statut::firstOrCreate(['name' => 'New'], ['color' => '#b51a00']);
        $statutGerm = Statut::firstOrCreate(['name' => 'Germinating'], ['color' => '#149215']);
        $statutGrowth = Statut::firstOrCreate(['name' => 'Growering'], ['color' => '#00a3d7']);
        $statutFlower = Statut::firstOrCreate(['name' => 'Flowering'], ['color' => '#ff8647']);
        $statutDry = Statut::firstOrCreate(['name' => 'Drying'], ['color' => '#fec700']);
        $statutCuring = Statut::firstOrCreate(['name' => 'Maturing'], ['color' => '#929292']);
        $statutReady = Statut::firstOrCreate(['name' => 'Ready'], ['color' => '#00f900']);
        $statutFinished = Statut::firstOrCreate(['name' => 'Finished'], ['color' => '#000000']);

        $tagSativa = Tag::firstOrCreate(['name' => 'Sativa'], ['color' => '#ff2600']);
        $tag8w = Tag::firstOrCreate(['name' => '8 weeks'], ['color' => '#d357fe']);
        Tag::firstOrCreate(['name' => 'Indica'], ['color' => '#008cb4']);
        Tag::firstOrCreate(['name' => 'Hybrid'], ['color' => '#f5ec00']);
        Tag::firstOrCreate(['name' => '9 weeks'], ['color' => '#be38f3']);
        Tag::firstOrCreate(['name' => '10 weeks'], ['color' => '#9929bd']);
        Tag::firstOrCreate(['name' => '11 weeks'], ['color' => '#7b219f']);
        Tag::firstOrCreate(['name' => '12 weeks'], ['color' => '#61177c']);

        $propsUrl = Property::firstOrCreate(['name' => 'URL']);
        $propsDesc = Property::firstOrCreate(['name' => 'Description']);
        $propsGene = Property::firstOrCreate(['name' => 'Genetics']);
        $propsParents = Property::firstOrCreate(['name' => 'Parents']);
        $propsThc = Property::firstOrCreate(['name' => '% THC']);
        $propsCbd = Property::firstOrCreate(['name' => '% CBD']);
        $propsSmells = Property::firstOrCreate(['name' => 'Smells']);
        $propsEffects = Property::firstOrCreate(['name' => 'Effects']);
        $propsStock = Property::firstOrCreate(['name' => 'Stock']);
        $propsAvgYield = Property::firstOrCreate(['name' => 'Average yield']);
        $propsSensor = Property::firstOrCreate(['name' => 'Sensor']);
        Property::firstOrCreate(['name' => 'FinalYield']);

        $checklistGerm = Checklist::firstOrCreate(['name' => 'Germination'], ['icon' => 'fas fa-leaf']);
        $checklistGrowth = Checklist::firstOrCreate(['name' => 'Growth'], ['icon' => 'fas fa-seedling']);
        $checklistFlower8w = Checklist::firstOrCreate(['name' => 'Flower 8 weeks'], ['icon' => 'fab fa-pagelines']);
        $checklistFlower9w = Checklist::firstOrCreate(['name' => 'Flower 9 weeks'], ['icon' => 'fab fa-pagelines']);
        $checklistFlower10w = Checklist::firstOrCreate(['name' => 'Flower 10 weeks'], ['icon' => 'fab fa-pagelines']);
        $checklistFlower11w = Checklist::firstOrCreate(['name' => 'Flower 11 weeks'], ['icon' => 'fab fa-pagelines']);
        $checklistFlower12w = Checklist::firstOrCreate(['name' => 'Flower 12 weeks'], ['icon' => 'fab fa-pagelines']);
        $checklistHarvest = Checklist::firstOrCreate(['name' => 'Harvest'], ['icon' => 'fas fa-jar']);

        $stageOrder = 1;
        $stages = [];
        foreach ([$checklistGerm, $checklistGrowth, $checklistFlower8w, $checklistFlower9w, $checklistFlower10w, $checklistFlower11w, $checklistFlower12w, $checklistHarvest] as $checklist) {
            $stage = Stage::firstOrCreate([
                'checklist_id' => $checklist->id,
            ], [
                'name' => $checklist->name,
                'order' => $stageOrder++,
                'interval_stage_days' => 0,
                'interval_item_days' => 7,
            ]);
            $stages[$checklist->id] = $stage;
        }

        $checklistStatut = [
            $checklistGerm->id => $statutGerm->id,
            $checklistGrowth->id => $statutGrowth->id,
            $checklistFlower8w->id => $statutFlower->id,
            $checklistFlower9w->id => $statutFlower->id,
            $checklistFlower10w->id => $statutFlower->id,
            $checklistFlower11w->id => $statutFlower->id,
            $checklistFlower12w->id => $statutFlower->id,
            $checklistHarvest->id => $statutDry->id,
        ];

        $checklistGrowth->parents()->sync([$checklistGerm->id]);
        $checklistFlower8w->parents()->sync([$checklistGrowth->id]);
        $checklistFlower9w->parents()->sync([$checklistGrowth->id]);
        $checklistFlower10w->parents()->sync([$checklistGrowth->id]);
        $checklistFlower11w->parents()->sync([$checklistGrowth->id]);
        $checklistFlower12w->parents()->sync([$checklistGrowth->id]);
        $checklistHarvest->parents()->sync([$checklistFlower8w->id, $checklistFlower9w->id, $checklistFlower10w->id, $checklistFlower11w->id, $checklistFlower12w->id]);

        $items = [];
        for ($i = 1; $i <= 4; $i++) {
            foreach ([$checklistGrowth, $checklistFlower8w, $checklistFlower9w, $checklistFlower10w, $checklistFlower11w, $checklistFlower12w] as $checklist) {
                $items[$checklist->id][$i] = Item::firstOrCreate([
                    'name' => sprintf('Week %d', $i),
                    'checklist_id' => $checklist->id,
                ], [
                    'parent_id' => $i > 1 ? $items[$checklist->id][$i - 1]->id : null,
                    'statut_id' => $checklistStatut[$checklist->id],
                ]);
            }
        }
        for ($i; $i <= 8; $i++) {
            foreach ([$checklistFlower8w, $checklistFlower9w, $checklistFlower10w, $checklistFlower11w, $checklistFlower12w] as $checklist) {
                $items[$checklist->id][$i] = Item::firstOrCreate([
                    'name' => sprintf('Week %d', $i),
                    'checklist_id' => $checklist->id,
                ], [
                    'parent_id' => $i > 1 ? $items[$checklist->id][$i - 1]->id : null,
                    'statut_id' => $checklistStatut[$checklist->id],
                ]);
            }
        }
        foreach ([$checklistFlower9w, $checklistFlower10w, $checklistFlower11w, $checklistFlower12w] as $checklist) {
            $items[$checklist->id][9] = Item::firstOrCreate([
                'name' => 'Week 9',
                'checklist_id' => $checklist->id,
            ], [
                'parent_id' => $items[$checklist->id][8]->id,
                'statut_id' => $checklistStatut[$checklist->id],
            ]);
        }
        foreach ([$checklistFlower10w, $checklistFlower11w, $checklistFlower12w] as $checklist) {
            $items[$checklist->id][10] = Item::firstOrCreate([
                'name' => 'Week 10',
                'checklist_id' => $checklist->id,
            ], [
                'parent_id' => $items[$checklist->id][9]->id,
                'statut_id' => $checklistStatut[$checklist->id],
            ]);
        }
        foreach ([$checklistFlower11w, $checklistFlower12w] as $checklist) {
            $items[$checklist->id][11] = Item::firstOrCreate([
                'name' => 'Week 11',
                'checklist_id' => $checklist->id,
            ], [
                'parent_id' => $items[$checklist->id][10]->id,
                'statut_id' => $checklistStatut[$checklist->id],
            ]);
        }
        $items[$checklistFlower12w->id][12] = Item::firstOrCreate([
            'name' => 'Week 12',
            'checklist_id' => $checklistFlower12w->id,
        ], [
            'parent_id' => $items[$checklistFlower12w->id][11]->id,
            'statut_id' => $checklistStatut[$checklistFlower12w->id],
        ]);

        $itemGerm = Item::firstOrCreate([
            'name' => 'Germinating',
            'checklist_id' => $checklistGerm->id,
        ], [
            'statut_id' => $checklistStatut[$checklistGerm->id],
            'parent_id' => null,
        ]);
        Item::firstOrCreate([
            'name' => 'Potting',
            'checklist_id' => $checklistGerm->id,
        ], [
            'parent_id' => $itemGerm->id,
            'statut_id' => $checklistStatut[$checklistGerm->id],
        ]);
        $itemHang = Item::firstOrCreate([
            'name' => 'Hanging',
            'checklist_id' => $checklistHarvest->id,
        ], [
            'statut_id' => $checklistStatut[$checklistHarvest->id],
            'parent_id' => null,
        ]);
        $itemDry = Item::firstOrCreate([
            'name' => 'Drying',
            'checklist_id' => $checklistHarvest->id,
        ], [
            'parent_id' => $itemHang->id,
            'statut_id' => $checklistStatut[$checklistHarvest->id],
        ]);
        Item::firstOrCreate([
            'name' => 'Curing',
            'checklist_id' => $checklistHarvest->id,
        ], [
            'parent_id' => $itemDry->id,
            'statut_id' => $checklistStatut[$checklistHarvest->id],
        ]);

        $strain = Strain::firstOrCreate(['name' => 'Strain 1']);
        $strainProps = [
            ['strain_id' => $strain->id, 'property_id' => $propsUrl->id, 'value' => 'https://google.com'],
            ['strain_id' => $strain->id, 'property_id' => $propsDesc->id, 'value' => 'Description test'],
            ['strain_id' => $strain->id, 'property_id' => $propsGene->id, 'value' => '75% Sativa'],
            ['strain_id' => $strain->id, 'property_id' => $propsParents->id, 'value' => 'Parent 1 x Parent 2'],
            ['strain_id' => $strain->id, 'property_id' => $propsThc->id, 'value' => '30'],
            ['strain_id' => $strain->id, 'property_id' => $propsCbd->id, 'value' => 'Low'],
            ['strain_id' => $strain->id, 'property_id' => $propsSmells->id, 'value' => 'Good'],
            ['strain_id' => $strain->id, 'property_id' => $propsEffects->id, 'value' => 'Strong'],
            ['strain_id' => $strain->id, 'property_id' => $propsStock->id, 'value' => '10'],
            ['strain_id' => $strain->id, 'property_id' => $propsAvgYield->id, 'value' => '750'],
        ];
        $strainTags = [
            ['strain_id' => $strain->id, 'tag_id' => $tagSativa->id],
            ['strain_id' => $strain->id, 'tag_id' => $tag8w->id],
        ];
        $strain->properties()->sync($strainProps);
        $strain->tags()->sync($strainTags);

        $plant = Plant::firstOrCreate([
            'name' => 'Plant 1',
            'strain_id' => $strain->id,
            'created_by' => $user->id,
        ], [
            'statut_id' => $statutNew->id,
        ]);
        $plantProps = [
            ['plant_id' => $plant->id, 'property_id' => $propsSensor->id, 'value' => 'A'],
        ];
        $plant->properties()->sync($plantProps);
        $plant->tags()->sync([]);

        $plant->stages()->sync([
            $stages[$checklistGerm->id]->id => ['initial' => true],
            $stages[$checklistGrowth->id]->id => ['initial' => false],
            $stages[$checklistFlower8w->id]->id => ['initial' => false],
            $stages[$checklistHarvest->id]->id => ['initial' => false],
        ]);

        $plant->items()->sync([
            $items[$checklistFlower8w->id][7]->id => ['flush' => true],
            $items[$checklistFlower8w->id][8]->id => ['flush' => true],
        ]);

        $board1 = Dashboard::firstOrCreate(['name' => 'Board 1']);
        $board2 = Dashboard::firstOrCreate(['name' => 'Board 2']);
        $board3 = Dashboard::firstOrCreate(['name' => 'Board 3']);
        $board4 = Dashboard::firstOrCreate(['name' => 'Board 4']);
        $board1->users()->sync([$user->id => ['creator' => true, 'default' => true]]);
        $board2->users()->sync([$user->id => ['creator' => true, 'default' => false]]);
        $board3->users()->sync([$user->id => ['creator' => true, 'default' => false]]);
        $board4->users()->sync([$user->id => ['creator' => true, 'default' => false]]);
        $board1->plants()->sync([$plant->id]);

        $statutFinished->exists();
        $statutCuring->exists();
    }
}
