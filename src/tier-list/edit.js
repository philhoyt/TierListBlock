import {
	InspectorControls,
	useBlockProps,
	useInnerBlocksProps,
} from '@wordpress/block-editor';
import { Button, PanelBody } from '@wordpress/components';
import { createBlock } from '@wordpress/blocks';
import { useDispatch } from '@wordpress/data';

const ALLOWED_BLOCKS = [ 'tier-list/tier-item' ];

const TEMPLATE = [
	[
		'tier-list/tier-item',
		{},
		[
			[
				'tier-list/tier-label',
				{ style: { color: { background: '#FF7F7F' } } },
				[ [ 'core/paragraph', { content: 'S', align: 'center' } ] ],
			],
			[ 'tier-list/tier-content', {} ],
		],
	],
	[
		'tier-list/tier-item',
		{},
		[
			[
				'tier-list/tier-label',
				{ style: { color: { background: '#FEBF7F' } } },
				[ [ 'core/paragraph', { content: 'A', align: 'center' } ] ],
			],
			[ 'tier-list/tier-content', {} ],
		],
	],
	[
		'tier-list/tier-item',
		{},
		[
			[
				'tier-list/tier-label',
				{ style: { color: { background: '#FFDF80' } } },
				[ [ 'core/paragraph', { content: 'B', align: 'center' } ] ],
			],
			[ 'tier-list/tier-content', {} ],
		],
	],
	[
		'tier-list/tier-item',
		{},
		[
			[
				'tier-list/tier-label',
				{ style: { color: { background: '#FFFF7F' } } },
				[ [ 'core/paragraph', { content: 'C', align: 'center' } ] ],
			],
			[ 'tier-list/tier-content', {} ],
		],
	],
	[
		'tier-list/tier-item',
		{},
		[
			[
				'tier-list/tier-label',
				{ style: { color: { background: '#C0FF7F' } } },
				[ [ 'core/paragraph', { content: 'D', align: 'center' } ] ],
			],
			[ 'tier-list/tier-content', {} ],
		],
	],
];

export default function Edit( { clientId } ) {
	const { insertBlock } = useDispatch( 'core/block-editor' );

	const addTierItem = () => {
		insertBlock(
			createBlock( 'tier-list/tier-item', {}, [
				createBlock(
					'tier-list/tier-label',
					{ style: { color: { background: '#C0FF7F' } } },
					[ createBlock( 'core/paragraph', { align: 'center' } ) ]
				),
				createBlock( 'tier-list/tier-content', {} ),
			] ),
			undefined,
			clientId
		);
	};

	const blockProps = useBlockProps();
	const innerBlocksProps = useInnerBlocksProps( blockProps, {
		allowedBlocks: ALLOWED_BLOCKS,
		template: TEMPLATE,
	} );

	return (
		<>
			<InspectorControls>
				<PanelBody title="Tiers">
					<Button variant="secondary" onClick={ addTierItem }>
						Add Tier Row
					</Button>
				</PanelBody>
			</InspectorControls>
			<div { ...innerBlocksProps } />
		</>
	);
}
