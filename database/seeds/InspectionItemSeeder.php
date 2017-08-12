<?php

use Illuminate\Database\Seeder;

class InspectionItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('inspection_item')->insert([
            'name' => 'Head Light',
            'form' => '[
                            {
                                "type": "radio-group",
                                "required": true,
                                "label": "Rating",
                                "inline": true,
                                "className": "",
                                "name": "radio-group-1495719902602",
                                "values": [
                                    {
                                        "label": "😃",
                                        "value": "1",
                                        "selected": true
                                    },
                                    {
                                        "label": "😐",
                                        "value": "2"
                                    },
                                    {
                                        "label": "☹️",
                                        "value": "3"
                                    }
                                ]
                            },
                            {
                                "type": "text",
                                "label": "Condition",
                                "placeholder": "Condition",
                                "className": "form-control",
                                "name": "text-1495719902626",
                                "subtype": "text",
                                "maxlength": "100"
                            }
                        ]',
            'typeId' => 1,
            'isActive' => 1
        ]);
        DB::table('inspection_item')->insert([
            'name' => 'Tail Light',
            'form' => '[
                            {
                                "type": "radio-group",
                                "required": true,
                                "label": "Rating",
                                "inline": true,
                                "className": "",
                                "name": "radio-group-1495720031404",
                                "values": [
                                    {
                                        "label": "😃",
                                        "value": "1",
                                        "selected": true
                                    },
                                    {
                                        "label": "😐",
                                        "value": "2"
                                    },
                                    {
                                        "label": "☹️",
                                        "value": "3"
                                    }
                                ]
                            },
                            {
                                "type": "text",
                                "label": "Condition",
                                "placeholder": "Condition",
                                "className": "form-control",
                                "name": "text-1495720031415",
                                "subtype": "text",
                                "maxlength": "100"
                            }
                        ]',
            'typeId' => 1,
            'isActive' => 1
        ]);
        DB::table('inspection_item')->insert([
            'name' => 'Front Left Tire',
            'form' => '[
                    {
                        "type": "radio-group",
                        "required": true,
                        "label": "Rating",
                        "inline": true,
                        "name": "radio-group-1502452774374",
                        "values": [
                            {
                                "label": "😃",
                                "value": "1",
                                "selected": true
                            },
                            {
                                "label": "😐",
                                "value": "2"
                            },
                            {
                                "label": "☹️",
                                "value": "3"
                            }
                        ]
                    },
                    {
                        "type": "textarea",
                        "label": "Condition",
                        "placeholder": "Condition",
                        "className": "form-control",
                        "name": "textarea-1502452774397",
                        "subtype": "textarea",
                        "maxlength": "100"
                    },
                    {
                        "type": "text",
                        "required": true,
                        "label": "PSI",
                        "className": "form-control",
                        "name": "text-1502452795394",
                        "subtype": "text"
                    }
                ]',
            'typeId' => 2,
            'isActive' => 1
        ]);
        DB::table('inspection_item')->insert([
            'name' => 'Front Right Tire',
            'form' => '[
                    {
                        "type": "radio-group",
                        "required": true,
                        "label": "Rating",
                        "inline": true,
                        "name": "radio-group-1502452815378",
                        "values": [
                            {
                                "label": "😃",
                                "value": "1",
                                "selected": true
                            },
                            {
                                "label": "😐",
                                "value": "2"
                            },
                            {
                                "label": "☹️",
                                "value": "3"
                            }
                        ]
                    },
                    {
                        "type": "textarea",
                        "label": "Condition",
                        "placeholder": "Condition",
                        "className": "form-control",
                        "name": "textarea-1502452815386",
                        "subtype": "textarea",
                        "maxlength": "100"
                    },
                    {
                        "type": "text",
                        "required": true,
                        "label": "PSI",
                        "className": "form-control",
                        "name": "text-1502452818386",
                        "subtype": "text"
                    }
                ]',
            'typeId' => 2,
            'isActive' => 1
        ]);
        DB::table('inspection_item')->insert([
            'name' => 'Rear Left Tire',
            'form' => '[
                    {
                        "type": "radio-group",
                        "required": true,
                        "label": "Rating",
                        "inline": true,
                        "name": "radio-group-1502452839609",
                        "values": [
                            {
                                "label": "😃",
                                "value": "1",
                                "selected": true
                            },
                            {
                                "label": "😐",
                                "value": "2"
                            },
                            {
                                "label": "☹️",
                                "value": "3"
                            }
                        ]
                    },
                    {
                        "type": "textarea",
                        "label": "Condition",
                        "placeholder": "Condition",
                        "className": "form-control",
                        "name": "textarea-1502452839621",
                        "subtype": "textarea",
                        "maxlength": "100"
                    },
                    {
                        "type": "text",
                        "required": true,
                        "label": "PSI",
                        "className": "form-control",
                        "name": "text-1502452842526",
                        "subtype": "text"
                    }
                ]',
            'typeId' => 2,
            'isActive' => 1
        ]);
        DB::table('inspection_item')->insert([
            'name' => 'Rear Right Tire',
            'form' => '[
                    {
                        "type": "radio-group",
                        "required": true,
                        "label": "Rating",
                        "inline": true,
                        "name": "radio-group-1502452861959",
                        "values": [
                            {
                                "label": "😃",
                                "value": "1",
                                "selected": true
                            },
                            {
                                "label": "😐",
                                "value": "2"
                            },
                            {
                                "label": "☹️",
                                "value": "3"
                            }
                        ]
                    },
                    {
                        "type": "textarea",
                        "label": "Condition",
                        "placeholder": "Condition",
                        "className": "form-control",
                        "name": "textarea-1502452861966",
                        "subtype": "textarea",
                        "maxlength": "100"
                    },
                    {
                        "type": "text",
                        "required": true,
                        "label": "PSI",
                        "className": "form-control",
                        "name": "text-1502452865914",
                        "subtype": "text"
                    }
                ]',
            'typeId' => 2,
            'isActive' => 1
        ]);
    }
}
