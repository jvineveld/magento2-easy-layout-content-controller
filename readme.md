# Magento2 plugin to control content defined in layout

Goal:
* Recognize page's current used cms page template, read the defined page_layout file, and extract options.
* Display options at cms page, so clients can only say what the content should be. The layout is predefined and unchangeable.

## Under development !! NOT WORKING
Like really really in development. nothing works, don't expect something.

## templates
This plugin requires you to create a folder, in the `[Your_Theme]/Magento_Theme/page_layout/` folder.
This folder should be named: "layout_update_xml_templates"
In this folder, there should be template file (.xml), don't care about the name.
The content's of this file should be the contents of the layout_update_xml of a cms page.

## template format:
I used regex to extract the template literals. I also check the corresponding line for the argument's information.

### Editable arguments

Format for editable: `%%editable: text : Title : This is the title %%`. When you add this as the value of a argument, the class is aware of the following things:
- Field type is 'text'
- The label for the field is 'Title'
- The description is 'This is the title'
- The name and type of the argument it's in

If you want to define a default value, you can just type a value after the template literal (inside the argument's content).

Example of a file:
For example: `[Your_Theme]/Magento_Theme/page_layout/layout_update_xml_templates/example-layout.xml`

### Define block wrappers
Format for block-title: `%%block-title: Title of the block %%`
Insert that after the `<referenceBlock name="block_name">` reference.

Block wrappers only have the title option

### Define layout title
Format for layout title: `* layout_info:title: Title of the layout `
Insert at the start of the document in a comment, on a separate rule exactly following the template above and below. (`<!-- -->`).

Layout info currently only has the title option

## example template xml

```xml
<!--
/**
* layout_info:title: Title of the layout
*/
-->
<referenceContainer name="container_name">
    <referenceBlock name="block_name"><!-- %%block-title: Title of the block %% -->
		<arguments>
          <argument name="parameter_name_1" xsi:type="string">%%editable: type : Title %%Default title</argument>
          <argument name="parameter_name_2" xsi:type="string">%%editable: image : Background image %%</argument>
        </arguments>
    </referenceBlock>
</referenceContainer>
```
