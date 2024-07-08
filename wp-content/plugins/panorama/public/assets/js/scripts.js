(function ($) {
  $(document).ready(function () {
    const panoContainer = document.querySelectorAll(".bppiv_panorama");
    Object.values(panoContainer).map((item) => {
      const settings = $(item).data("settings");

      $(item).removeAttr("data-settings");

      const { bppiv_type, bppiv_image_src, bppiv_video_src, bppiv_auto_play, bppiv_video_mute, bppiv_video_loop, image_src_360, bppiv_auto_rotate, bppiv_speed, bppiv_auto_load, control_show_hide, draggable_360, compass_360, title_author, title_360, author_360, initial_view, initial_view_property: viewProperty, custom_control } = settings;
      //Video options
      if (bppiv_type === "video") {
        const videoeSource = bppiv_video_src ? bppiv_video_src.url : [];
        const isAutoPlay = Boolean(parseInt(bppiv_auto_play));
        const isVideoMute = Boolean(parseInt(bppiv_video_mute));
        const isVideoLoop = Boolean(parseInt(bppiv_video_loop));
        const isHideControl = !Boolean(parseInt(bppiv_video_loop));

        const panoramaVideo = new PANOLENS.VideoPanorama(videoeSource, {
          autoplay: isAutoPlay,
          loop: isVideoLoop,
          muted: isVideoMute,
        });

        const panoramaViewer = new PANOLENS.Viewer({
          container: item,
          controlBar: isHideControl,
        });
        panoramaViewer.add(panoramaVideo);
        //Image360 options
      } else if (bppiv_type === "image360") {
        const image360 = image_src_360 ?? "";
        const isAutoRotate = Boolean(parseInt(bppiv_auto_rotate));
        const rotateSpeed = isAutoRotate ? bppiv_speed : 0;
        const autoLoad = Boolean(parseInt(bppiv_auto_load));
        const isHideControl = !Boolean(parseInt(control_show_hide));
        const isDraggable = Boolean(parseInt(draggable_360));
        const titleAuthor = title_author ?? 0;
        const compass360 = Boolean(parseInt(compass_360));
        const initialView =
          (Boolean(parseInt(initial_view)) && {
            pitch: parseFloat(viewProperty.top),
            yaw: parseFloat(viewProperty.right),
            hfov: parseFloat(viewProperty.bottom),
          }) ||
          {};

        // container id
        const panoId = $(item).attr("id");

        const titleAuthorOptions = titleAuthor === "1" ? { title: title_360, author: author_360 } : {};
        viewer = pannellum.viewer(panoId, {
          type: "equirectangular",
          panorama: image360,
          preview: image360,
          autoLoad: autoLoad,
          autoRotate: rotateSpeed,
          draggable: isDraggable,
          showControls: isHideControl,
          compass: compass360,
          //northOffset: -160.5,
          orientationOnByDefault: true,
          ...titleAuthorOptions,
          ...initialView,
        });
      } else {
        const imageSource = bppiv_image_src ? bppiv_image_src.url : [];
        const isAutoRotate = Boolean(parseInt(bppiv_auto_rotate));
        const autoRotateSpeed = bppiv_speed ? bppiv_speed : 2;
        const isHideControl = !Boolean(parseInt(control_show_hide));

        const panorama = new PANOLENS.ImagePanorama(imageSource);
        const viewer = new PANOLENS.Viewer({
          container: item,
          autoRotate: isAutoRotate,
          autoRotateSpeed: parseFloat(autoRotateSpeed),
          controlBar: isHideControl,
        });
        viewer.add(panorama);
      }
    });
  });
})(jQuery);
