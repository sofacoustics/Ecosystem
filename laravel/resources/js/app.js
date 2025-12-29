import './bootstrap';

//jw:note removing these three lines, since 'livewire' includes alpine (https://livewire.laravel.com/docs/troubleshooting#removing-laravel-breezes-alpine)
//import Alpine from 'alpinejs';
//window.Alpine = Alpine;
//Alpine.start();

// Import Viewerjs for the Image Widget
import 'viewerjs/dist/viewer.css';
import Viewer from 'viewerjs';
window.Viewer = Viewer; // Make it available globally so you can use it in inline <script> tags
