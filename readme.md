# Magento2 plugin to control content defined in layout

Goal:
* Recognize page's current used cms page template, read the defined page_layout file, and extract options.
* Display options at cms page, so clients can only say what the content should be. The layout is predefined and unchangable.

## Under development !! NOT WORKING

## template format:
I used regex to extract the template literals. I also check the corrosponding line for the argument's information.

### Editable arguments

Format for editable: `%%editable: text : Title : This is the title %%` as value of a argument, the class know's the following things:
- Field type is 'text'
- The label for the field is 'Title'
- The description is 'This is the title'

If you want to define a default value, you can just type a value after the template literal (inside the argument's content).

Example of a file:
For example: `[theme]/Magento_Theme/page_layout/layout_update_xml_templates/example-layout.xml`

### Define block wrappers
Format for block-title: `%%block-title: Title of the block %%`
Insert that after the `<referenceBlock name="visual_block">` reference.

Block wrappers only have the title option

### Define layout title
Format for layout title: `* layout_info:title: Title of the layout `
Insert at the start of the document in a comment, on a seperate rule exactly following the template above and below. (`<!-- -->`).

Layout info currently only has the title option

```xml
<!--
/**
* layout_info:title: Title of the layout
*/
-->
<referenceContainer name="visual">
    <referenceBlock name="visual_block"><!-- %%block-title: Title of the block %% -->
		<arguments>
          <argument name="title" xsi:type="string">%%editable: type : Title %%Default title</argument>
          <argument name="background_image" xsi:type="string">%%editable: image : Achtergrond afbeelding %%</argument>
        </arguments>
    </referenceBlock>
</referenceContainer>
```
