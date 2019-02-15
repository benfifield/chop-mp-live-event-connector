USE [MinistryPlatform]
GO

/****** Object:  Table [dbo].[ChOP_Current_Event]    Script Date: 2/14/2019 9:57:10 AM ******/
SET ANSI_NULLS ON
GO

SET QUOTED_IDENTIFIER ON
GO

CREATE TABLE [dbo].[ChOP_Current_Event](
	[ChOP_Current_Event_ID] [int] IDENTITY(1,1) NOT NULL,
	[Live] [bit] NOT NULL,
	[Start_Date] [datetime] NOT NULL,
	[Domain_ID] [int] NOT NULL,
	[Title] [nvarchar](50) NULL,
 CONSTRAINT [PK_ChOP_Current_Event] PRIMARY KEY CLUSTERED 
(
	[ChOP_Current_Event_ID] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO


