import { registerBlockType } from '@wordpress/blocks';

import './style.scss';

// Register nested tier block
import './result-tier';

/**
 * Internal dependencies
 */
import Edit from './edit';
import save from './save';
import metadata from './block.json';

// Register the food quiz block
registerBlockType( metadata.name, {
	/**
	 * @see ./edit.js
	 */
	edit: Edit,

	/**
	 * @see ./save.js
	 */
	save,
} );
