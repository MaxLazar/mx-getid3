# MX GetID3

**MX GetID3** is a PHP script that extracts useful information from MP3s & other multimedia file formats for  ExpressionEngine

## Supported formats
* Tag formats:
* * ID3v1 (v1.0 & v1.1)
* * ID3v2 (v2.2, v2.3 & v2.4)
* * APE tags (v1 & v2)
* * (Ogg) VorbisComment
* * Lyrics3 (v1 & v2)
* * IDivX
* * Lossy Audio-only formats:
* * MP3, MP2, MP1 (MPEG-1, layer III/II/I audio, including Fraunhofer, Xing and LAME VBR/CBR headers)
* * Ogg Vorbis
* * Musepack (versions SV4-SV8)
* * AAC & MP4
* * AC-3
* * DTS
* * RealAudio
* * VQF
* * Speex
* * Digital Speech Standard (DSS)
* * Audible Audiobooks
* Lossless Audio-only formats:
* * WAV (including extended chunks such as BWF and CART)
* * AIFF
* * Monkey's Audio
* * FLAC & OggFLAC
* * LA (Lossless Audio)
* * OptimFROG
* * WavPack
* * TTA
* * LPAC (Lossless Predictive Audio Compressor)
* * Bonk
* * LiteWave
* * Shorten
* * RKAU
* * Apple Lossless Audio Codec
* * RealAudio Lossless
* * CD-audio (*.cda)
* * NeXT/Sun .au
* * Creative .voc
* * AVR (Audio Visual Research)
* * MIDI
* Audio-Video formats:
* * AVI
* * Matroska (WebM)
* * ASF (ASF, Windows Media Audio (WMA), Windows Media Video (WMV))
* * MPEG-1 & MPEG-2
* * Quicktime
* * RealVideo
* * NSV (Nullsoft Streaming Video)
* Graphic formats:
* * JPEG
* * PNG
* * GIF
* * BMP (Windows & OS/2)
* * TIFF
* * SWF (Flash)
* * PhotoCD
* Data formats:
* * ZIP
* * TAR
* * GZIP
* * ISO 9660 (CD-ROM image)
* * CUEsheets (.cue)
* * SZIP
* Metadata types:
* * EXIF (Exchangeable image file format)
* * IPTC
* * XMP (Adobe Extensible Metadata Platform)
* Formats identified, but not parsed:
* * PDF
* * RAR
* * MS Office (.doc, .xls, etc)

## Installation
* Download the latest version of MX GetID3 and extract the .zip to your desktop.
* Copy *mx_getid3* to */system/user/addons/*

## Configuration
Once the Plugin is installed, you should be able to see it listed in the Add-On Manager in your ExpressionEngine Control Panel. Is no needs in any control panel activation or configuration.

## Template Tags
{exp:mx_calc}

**JPEG:**

	{exp:mx_getid3 file='/DSC_4381.JPG'}
	    {video}
	    <p>Size: {resolution_x}x{resolution_y}</p>
	    {/video}
	    {xmp}
	        {aux}
	        <p>Lens: {Lens}</p>
	        <p>LensInfo: {LensInfo}</p>
	
	        {/aux}
	    {/xmp}
	{/exp:mx_getid3}</div>

**JPEG Output:**

	Size: 1600x1060
	
	Lens: AF-S Nikkor 50mm f/1.4G
	
	LensInfo: 50/1 50/1 7/5 7/5


**MP3:**

	{exp:mx_getid3 file='Some Kind of Heaven.mp3' debug="true" }
	    <p>File Name: {filename}</p>
	    <p>Length: {playtime_seconds}</p>
	    <p>Bitrate: {bitrate}</p>
	    <p>Playtime: {playtime_string}</p>
	
	{tags}
	    {id3v2}
	    <p>Title: {title}</p>
	    <p>Artist: {artist}</p>
	    <p>Album: {album}</p>
	    <p>Year: {year}</p>
	    <p>Genre: {genre}</p>
	    <p>Publisher: {publisher}</p>
	    {/id3v2}
	{/tags}
	{/exp:mx_getid3}

**MP3 Output:**

	File Name: Brain Rules-Part01.mp3

	Length: 4534.67428571

	Playtime: 1:15:35

	Title: Brain Rules - Part 1

	Artist: John Medina

	Album: Brain Rules - 12 Principles fo

	Year:

	Genre: Other

### Parameters


*file*

Path to file (**full PATH!** not URL)

*refresh*

Refresh is the number of minutes between cache refreshes.

*debug*

Description of all tags which you can use with getID3 library can take more time than writing this plugin. So, if you want to see available tags for the target file, just add the debug parameter to see it.

*debug = "on" *

**IMPORTANT**

Variables can be Single or Pairs.


## Support Policy
This is Communite Edition add-on.

## Contributing To MX GetID3 for ExpressionEngine

Your participation to MX GetID3 development is very welcome!

You may participate in the following ways:

* [Report issues](https://github.com/MaxLazar/mx-getid3-ee3/issues)
* Fix issues, develop features, write/polish documentation
Before you start, please adopt an existing issue (labelled with "ready for adoption") or start a new one to avoid duplicated efforts.
Please submit a merge request after you finish development.


### License

The MX GetID3 is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)
