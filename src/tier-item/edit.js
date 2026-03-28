import { useBlockProps, useInnerBlocksProps } from '@wordpress/block-editor';

const TEMPLATE = [
	[ 'tier-list/tier-label', {} ],
	[ 'tier-list/tier-content', {} ],
];

export default function Edit() {
	const blockProps = useBlockProps();
	const innerBlocksProps = useInnerBlocksProps( blockProps, {
		template: TEMPLATE,
		templateLock: 'all',
	} );
	return <div { ...innerBlocksProps } />;
}
