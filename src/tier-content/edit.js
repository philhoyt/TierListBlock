import { useBlockProps, useInnerBlocksProps } from '@wordpress/block-editor';
import { store as blocksStore } from '@wordpress/blocks';
import { useSelect } from '@wordpress/data';

// Blocks that auto-transform when dragged adjacent to each other.
const DISALLOWED_BLOCKS = [ 'core/gallery', 'core/group' ];

export default function Edit() {
	const allowedBlocks = useSelect( ( select ) =>
		select( blocksStore )
			.getBlockTypes()
			.map( ( { name } ) => name )
			.filter( ( name ) => ! DISALLOWED_BLOCKS.includes( name ) )
	);

	const blockProps = useBlockProps();
	const innerBlocksProps = useInnerBlocksProps( blockProps, {
		allowedBlocks,
		templateLock: false,
	} );
	return <div { ...innerBlocksProps } />;
}
