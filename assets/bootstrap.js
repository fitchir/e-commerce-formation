//import { startStimulusApp } from '@symfony/stimulus-bundle';

// const app = startStimulusApp();
// register any custom, 3rd party controllers here
// app.register('some_controller_name', SomeImportedController);

import { Application } from '@hotwired/stimulus'
import { definitionsFromContext } from '@hotwired/stimulus-webpack-helpers'

window.Stimulus = Application.start()
const context = require.context('./controllers', true, /\.js$/)
Stimulus.load(definitionsFromContext(context))
