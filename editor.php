<?php

class WP_Image_Editor_GD_Improved extends WP_Image_Editor_GD {
	private $iptc = null;

	private function get_iptc() {
		if ( ! $this->iptc ) {
			$image = getimagesize( $this->file, $info );

			if ( isset( $info['APP13'] ) ) {
				$this->iptc = $info['APP13'];
			}
			else {
				$this->iptc = $this->image_metadata_to_iptc();
			}
		}

		return $this->iptc;
	}

	protected function _save( $image, $filename = null, $mime_type = null ) {
		$file_info = parent::_save( $image, $filename, $mime_type );
		$iptc      = $this->get_iptc();

		if ( $iptc ) {
			$content = iptcembed( $iptc, $file_info['path'] );

			if ( $content ) {
				$fp = fopen( $file_info['path'], "wb");
				fwrite( $fp, $content );
				fclose( $fp );
			}
		}

		return $file_info;
	}



	private function image_metadata_to_iptc() {
		$iptc = array();
		$meta = wp_read_image_metadata( $this->file );

		$iptc['2#105'] = $meta['title'];
		$iptc['2#110'] = $meta['credit'];
		$iptc['2#116'] = $meta['copyright'];

		$iptc['2#055'] = date( 'Y-m-d', $meta['created_timestamp'] );
		$iptc['2#060'] = date( 'H:i:s', $meta['created_timestamp'] );

		$data = '';

		foreach ( $iptc as $tag => $string ) {
			$tag   = substr( $tag, 2 );
			$data .= $this->iptc_make_tag( 2, $tag, $string );
		}

		return $data;
	}

	// iptc_make_tag() function by Thies C. Arntzen
	private function iptc_make_tag( $rec, $data, $value )
	{
		$length = strlen($value);
		$retval = chr(0x1C) . chr($rec) . chr($data);

		if ($length < 0x8000) {
			$retval .= chr($length >> 8) .  chr($length & 0xFF);
		}
		else {
			$retval .= chr( 0x80 ) . 
					   chr( 0x04 ) . 
					   chr( ($length >> 24) & 0xFF ) . 
					   chr( ($length >> 16) & 0xFF ) . 
					   chr( ($length >> 8) & 0xFF ) . 
					   chr( $length & 0xFF );
		}

		return $retval . $value;
	}

}