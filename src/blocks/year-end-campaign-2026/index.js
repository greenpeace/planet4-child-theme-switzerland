import { registerBlockType } from '@wordpress/blocks';

import './style.scss';
import Edit from './edit';
import metadata from './block.json';
import save from './save';

registerBlockType( metadata.name, {
	edit: Edit,
	save,
} );
