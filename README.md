# Primary ICT Support - Builder Add-ons for Themify

This WordPress plugin adds two modules to Themify Builder.

- **Sub Menu** shows child pages as blocks or a list. List mode uses the current page's children, then the parent's children, then the selected WordPress menu. The optional final fallback lists all pages.
- **Add File** creates buttons for linked files and supports download, new tab and lightbox options.

## Updating from 0.1.4

If 0.1.5 appears on the **Plugins** screen, click **Update now**. The 0.1.4 updater can fail during the update check and omits the plugin identifier required for unattended WordPress updates.

If the update check fails, download `picts-builder-addons-0.1.5.zip` from the GitHub release. Use **Plugins → Add New Plugin → Upload Plugin** and choose **Replace current with uploaded**. If wp-admin is unavailable, back up and replace the existing `wp-content/plugins/picts-builder-addons` folder with the extracted folder through your hosting file manager or SFTP.

## Checks

Run `php tests/updater.php` from the source directory to check release validation and package selection.
