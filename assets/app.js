import './styles/app.css'; // Import your Tailwind CSS file

// Stimulus setup
import { startStimulusApp } from '@symfony/stimulus-bridge';

// Registers Stimulus controllers from controllers.json
export const app = startStimulusApp(require.context(
    './controllers',
    true,
    /\.js$/
));