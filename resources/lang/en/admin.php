<?php

return [

    'sidebar' => [
    	'admin' => 'Admin',
    	'menu' => [
	        'dashboard' => 'Dashboard',
			'provinces' => 'Manage Province',
			'city' => 'Manage Cities',
			'users' => 'Manage Users',
			'engineers' => 'Manage Engineers',
			'managers' => 'Manage Managers',
			'admin' => 'Manage Admin',
			'tools' => 'Manage Tools',
            'manage_tool_requests' => 'Manage Tool Requests',
            'manage_tool_return' => 'Tool Return Requests',
			'sites' => 'Manage Sites',
			'modality' => 'Manage Modalities',
			'sub-menu' => [
				'add' => 'Add',
				'view' => 'View',
			],
	    ],
	],

	'navbar' => [
    	'menu' => [
	        'home' => 'Home',
			'contact' => 'Contact',
	    ],
	],

	'page' => [
		'search' => 'Search',
		'next' => 'Next',
		'previous' => 'Previous',
	],

	'users' => [
		'name' => 'Users',
		'add' => 'Add Users',
		'update' => 'Update Users',
		'list' => 'Users List',
		'fields' => [
			'name' => 'Name',
			'emp_no_required' => 'Please enter employee no.',
            'emp_no_min' => 'Employee no. should be between 1 and 15 characters',
            'emp_no_max' => 'Employee no. should be between 1 and 15 characters',
            'emp_no_unique' => 'Employee no. has already been taken.',
			'empId' => 'Employee Id',
			'password' => 'Password',
			'mobile' => 'Mobile No.',
			'role' => 'Role',
            'emp_no' => 'Employee No.',
            'emp_email' => 'Email',
		],
	],

	'tools' => [
		'add' => 'Add Tool',
		'update' => 'Update Tools',
		'list' => 'Tools List',
		'fields' => [
			'tool_id' => 'Tool Number',
			'asset' => 'Asset',
			'sort_field' => 'Sort Field',
			'description' => 'Description',
			'type_of_use' => 'Type of Use',
			'calibration_date' => 'Calibration Date',
			'status' => 'Status',
			'image' => 'Image',
            'QR' => 'QR Code',

		],
	],

	'sites' => [
		'name' => 'Sites',
		'add' => 'Add Sites',
		'update' => 'Update Sites',
		'list' => 'Sites List',
		'fields' => [
			'name' => 'Name',
			'address' => 'Address',
			'description' => 'Description',
			'type' => 'Type',
		],
	],

	'modality' => [
		'name' => 'Modalities',
		'add' => 'Add Modality',
		'update' => 'Update Modality',
		'list' => 'Modalities List',
		'fields' => [
			'name' => 'Name',
		],
	],

    'manage_tool_requests' => [
        'name' => 'Manage Tool Requests',
        'list' => 'Tool Requests List',
        'fields' => [
            'name' => 'Name',
        ],
    ],

    'manage_tool_return' => [
        'name' => 'Manage Tool Return Requests',
        'add' => 'Add Modality',
        'update' => 'Update Modality',
        'list' => 'Tool Return Requests List',
        'fields' => [
            'name' => 'Name',
        ],
    ],
];
